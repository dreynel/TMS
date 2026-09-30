<?php

namespace App\Services;

use App\Contracts\BorrowingRepositoryInterface;
use App\Contracts\ToolRepositoryInterface;
use App\Enums\BorrowStatus;
use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Models\Borrowing;
use App\Models\ToolLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class BorrowingService
{
    public function __construct(
        protected BorrowingRepositoryInterface $borrowingRepo,
        protected ToolRepositoryInterface $toolRepo
    ) {}

    /**
     * Submit a borrowing request
     */
    public function submitRequest(int $borrowerId, string $purpose, string $requestDate, string $expectedReturnDate, array $items): array
    {
        $borrower = User::find($borrowerId);
        if (!$borrower) {
            return ['success' => false, 'message' => 'Borrower account not found.'];
        }

        // 1. Enforce Approval Check
        if ($borrower->isBorrower() && !$borrower->is_approved) {
            return [
                'success' => false,
                'message' => 'Your borrower account is pending approval by BIND-Tech Tool Custodian/Admin.'
            ];
        }

        // 2. Enforce Campus Rule #3: Overdue suspension
        if ($borrower->hasOverdueBorrowings()) {
            return [
                'success' => false,
                'message' => 'You currently have overdue equipment. Borrowing privileges are suspended until all overdue tools are returned and cleared.'
            ];
        }

        // 3. Aggregate quantities by tool_id in case user selected the same tool multiple times
        $aggregated = [];
        foreach ($items as $item) {
            $toolId = (int) ($item['tool_id'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);
            if ($qty > 0 && $toolId > 0) {
                $aggregated[$toolId] = ($aggregated[$toolId] ?? 0) + $qty;
            }
        }

        if (empty($aggregated)) {
            return ['success' => false, 'message' => 'Please select at least one valid tool to borrow.'];
        }

        // 4. Validate stock availability
        $itemsData = [];
        foreach ($aggregated as $toolId => $totalRequestedQty) {
            $tool = $this->toolRepo->findById($toolId);
            if (!$tool) {
                return ['success' => false, 'message' => 'Selected tool does not exist.'];
            }
            if ($tool->available_qty < $totalRequestedQty) {
                return [
                    'success' => false,
                    'message' => "Tool '{$tool->name}' only has {$tool->available_qty} available unit(s), but {$totalRequestedQty} was requested."
                ];
            }

            $itemsData[] = [
                'tool_id' => $toolId,
                'quantity' => $totalRequestedQty,
                'condition_upon_release' => $tool->condition->value ?? ToolCondition::GOOD->value,
            ];
        }

        // 5. Generate unique borrow code: BRW-YYYYMMDD-XXXX
        do {
            $borrowCode = 'BRW-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        } while (Borrowing::where('borrow_code', $borrowCode)->exists());

        $borrowingData = [
            'borrow_code' => $borrowCode,
            'borrower_id' => $borrowerId,
            'purpose' => $purpose,
            'request_date' => $requestDate,
            'expected_return_date' => $expectedReturnDate,
            'status' => BorrowStatus::PENDING->value,
        ];

        $borrowing = $this->borrowingRepo->createBorrowing($borrowingData, $itemsData);

        return [
            'success' => true,
            'message' => 'Borrowing request submitted successfully!',
            'borrowing' => $borrowing
        ];
    }

    /**
     * Approve a borrowing request
     */
    public function approveRequest(int $borrowingId, int $custodianId, ?string $notes = null): bool
    {
        $borrowing = $this->borrowingRepo->findById($borrowingId);
        if (!$borrowing || !$borrowing->canBeApproved()) {
            return false;
        }

        return $this->borrowingRepo->updateStatus($borrowingId, BorrowStatus::APPROVED->value, $custodianId, $notes);
    }

    /**
     * Reject a borrowing request
     */
    public function rejectRequest(int $borrowingId, int $custodianId, string $reason): bool
    {
        $borrowing = $this->borrowingRepo->findById($borrowingId);
        if (!$borrowing || !$borrowing->canBeRejected()) {
            return false;
        }

        return $this->borrowingRepo->updateStatus($borrowingId, BorrowStatus::REJECTED->value, $custodianId, $reason);
    }

    /**
     * Release tools (Checkout) to borrower with atomic transaction
     */
    public function releaseTools(int $borrowingId, int $custodianId): array
    {
        $borrowing = $this->borrowingRepo->findById($borrowingId);
        if (!$borrowing) {
            return ['success' => false, 'message' => 'Borrowing request not found.'];
        }

        if (!$borrowing->canBeReleased()) {
            return ['success' => false, 'message' => 'Only pending or approved requests can be released.'];
        }

        return DB::transaction(function () use ($borrowing, $custodianId) {
            // Verify all items have enough stock before making any updates
            foreach ($borrowing->items as $item) {
                // Refresh tool to get latest stock in transaction
                $tool = $this->toolRepo->findById($item->tool_id);
                if (!$tool || $tool->available_qty < $item->quantity_requested) {
                    $toolName = $tool ? $tool->name : 'Item';
                    $avail = $tool ? $tool->available_qty : 0;
                    return [
                        'success' => false,
                        'message' => "Insufficient stock to release '{$toolName}'. Available: {$avail} unit(s)."
                    ];
                }
            }

            // Deduct inventory stock
            foreach ($borrowing->items as $item) {
                $item->update(['quantity_released' => $item->quantity_requested]);
                $this->toolRepo->updateStockAndCondition($item->tool_id, -$item->quantity_requested);
            }

            $this->borrowingRepo->updateStatus($borrowing->id, BorrowStatus::RELEASED->value, $custodianId, 'Tools handed over to borrower.');

            return ['success' => true, 'message' => 'Tools released successfully to borrower.'];
        });
    }

    /**
     * Process tool return & condition update with atomic transaction
     */
    public function processReturn(int $borrowingId, int $custodianId, array $itemConditions, ?string $notes = null): array
    {
        $borrowing = $this->borrowingRepo->findById($borrowingId);
        if (!$borrowing) {
            return ['success' => false, 'message' => 'Borrowing record not found.'];
        }

        if (!$borrowing->canBeReturned()) {
            return ['success' => false, 'message' => 'Only active released or overdue borrowings can be returned.'];
        }

        return DB::transaction(function () use ($borrowing, $custodianId, $itemConditions, $notes) {
            foreach ($borrowing->items as $item) {
                $conditionReturn = $itemConditions[$item->id]['condition'] ?? ToolCondition::GOOD->value;
                $returnNotes = $itemConditions[$item->id]['notes'] ?? null;

                $item->update([
                    'condition_upon_return' => $conditionReturn,
                    'return_notes' => $returnNotes,
                ]);

                // Determine if tool status should be changed (e.g. damaged or needs repair)
                $newToolStatus = null;
                if (in_array($conditionReturn, [ToolCondition::DAMAGED->value, ToolCondition::NEEDS_REPAIR->value])) {
                    $newToolStatus = ToolStatus::DAMAGED->value;
                }

                // Restore stock & set condition
                $this->toolRepo->updateStockAndCondition(
                    $item->tool_id,
                    $item->quantity_released,
                    $newToolStatus,
                    $conditionReturn
                );

                // Create a condition audit log if damaged/repaired upon return
                if ($newToolStatus === ToolStatus::DAMAGED->value) {
                    ToolLog::create([
                        'tool_id' => $item->tool_id,
                        'user_id' => $custodianId,
                        'action' => 'damaged_on_return',
                        'previous_condition' => $item->condition_upon_release->value ?? 'good',
                        'new_condition' => $conditionReturn,
                        'previous_status' => ToolStatus::IN_USE->value,
                        'new_status' => ToolStatus::DAMAGED->value,
                        'remarks' => "Returned by {$borrowing->borrower->name} [{$borrowing->borrow_code}] with notes: {$returnNotes}",
                    ]);
                }
            }

            $this->borrowingRepo->updateStatus($borrowing->id, BorrowStatus::RETURNED->value, $custodianId, $notes);

            return ['success' => true, 'message' => 'Tools returned and inventory condition updated successfully.'];
        });
    }

    /**
     * Scan and mark overdue active borrowings
     */
    public function checkAndUpdateOverdue(): int
    {
        $releasedBorrowings = Borrowing::where('status', BorrowStatus::RELEASED->value)
            ->where('expected_return_date', '<', now())
            ->get();

        $count = 0;
        foreach ($releasedBorrowings as $borrowing) {
            $borrowing->update(['status' => BorrowStatus::OVERDUE->value]);
            $count++;
        }

        return $count;
    }
}
