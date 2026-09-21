<?php

namespace App\Services;

use App\Contracts\BorrowingRepositoryInterface;
use App\Contracts\ToolRepositoryInterface;
use App\Enums\BorrowStatus;
use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Models\Borrowing;

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
        // 1. Validate availability of items
        foreach ($items as $item) {
            $tool = $this->toolRepo->findById($item['tool_id']);
            if (!$tool) {
                return ['success' => false, 'message' => 'Selected tool does not exist.'];
            }
            if ($tool->available_qty < $item['quantity']) {
                return [
                    'success' => false,
                    'message' => "Tool '{$tool->name}' only has {$tool->available_qty} available unit(s)."
                ];
            }
        }

        // 2. Generate unique borrow code: BRW-YYYYMMDD-XXXX
        $borrowCode = 'BRW-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        $borrowingData = [
            'borrow_code' => $borrowCode,
            'borrower_id' => $borrowerId,
            'purpose' => $purpose,
            'request_date' => $requestDate,
            'expected_return_date' => $expectedReturnDate,
            'status' => BorrowStatus::PENDING->value,
        ];

        $itemsData = array_map(function ($item) {
            $tool = $this->toolRepo->findById($item['tool_id']);
            return [
                'tool_id' => $item['tool_id'],
                'quantity' => $item['quantity'],
                'condition_upon_release' => $tool->condition->value ?? ToolCondition::GOOD->value,
            ];
        }, $items);

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
        return $this->borrowingRepo->updateStatus($borrowingId, BorrowStatus::APPROVED->value, $custodianId, $notes);
    }

    /**
     * Reject a borrowing request
     */
    public function rejectRequest(int $borrowingId, int $custodianId, string $reason): bool
    {
        return $this->borrowingRepo->updateStatus($borrowingId, BorrowStatus::REJECTED->value, $custodianId, $reason);
    }

    /**
     * Release tools (Checkout) to borrower
     */
    public function releaseTools(int $borrowingId, int $custodianId): array
    {
        $borrowing = $this->borrowingRepo->findById($borrowingId);
        if (!$borrowing) {
            return ['success' => false, 'message' => 'Borrowing request not found.'];
        }

        if (!in_array($borrowing->status, [BorrowStatus::PENDING, BorrowStatus::APPROVED])) {
            return ['success' => false, 'message' => 'Only pending or approved requests can be released.'];
        }

        // Deduct inventory stock
        foreach ($borrowing->items as $item) {
            if ($item->tool->available_qty < $item->quantity_requested) {
                return [
                    'success' => false,
                    'message' => "Insufficient stock to release '{$item->tool->name}'."
                ];
            }
            $item->update(['quantity_released' => $item->quantity_requested]);
            $this->toolRepo->updateStockAndCondition($item->tool_id, -$item->quantity_requested);
        }

        $this->borrowingRepo->updateStatus($borrowingId, BorrowStatus::RELEASED->value, $custodianId, 'Tools handed over to borrower.');

        return ['success' => true, 'message' => 'Tools released successfully to borrower.'];
    }

    /**
     * Process tool return & condition update
     */
    public function processReturn(int $borrowingId, int $custodianId, array $itemConditions, ?string $notes = null): array
    {
        $borrowing = $this->borrowingRepo->findById($borrowingId);
        if (!$borrowing) {
            return ['success' => false, 'message' => 'Borrowing record not found.'];
        }

        foreach ($borrowing->items as $item) {
            $conditionReturn = $itemConditions[$item->id]['condition'] ?? ToolCondition::GOOD->value;
            $returnNotes = $itemConditions[$item->id]['notes'] ?? null;

            $item->update([
                'condition_upon_return' => $conditionReturn,
                'return_notes' => $returnNotes,
            ]);

            // Restore stock & set tool condition
            $newToolStatus = null;
            if (in_array($conditionReturn, [ToolCondition::DAMAGED->value, ToolCondition::NEEDS_REPAIR->value])) {
                $newToolStatus = ToolStatus::DAMAGED->value;
            }

            $this->toolRepo->updateStockAndCondition(
                $item->tool_id,
                $item->quantity_released,
                $newToolStatus,
                $conditionReturn
            );
        }

        $this->borrowingRepo->updateStatus($borrowingId, BorrowStatus::RETURNED->value, $custodianId, $notes);

        return ['success' => true, 'message' => 'Tools returned and inventory condition updated successfully.'];
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
