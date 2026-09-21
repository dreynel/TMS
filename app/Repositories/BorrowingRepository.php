<?php

namespace App\Repositories;

use App\Contracts\BorrowingRepositoryInterface;
use App\Enums\BorrowStatus;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class BorrowingRepository implements BorrowingRepositoryInterface
{
    public function findById(int $id): ?Borrowing
    {
        return Borrowing::with(['borrower', 'custodian', 'items.tool.category'])->find($id);
    }

    public function findByBorrowCode(string $code): ?Borrowing
    {
        return Borrowing::with(['borrower', 'custodian', 'items.tool.category'])
            ->where('borrow_code', $code)
            ->first();
    }

    public function getPendingRequests(): Collection
    {
        return Borrowing::with(['borrower', 'items.tool'])
            ->where('status', BorrowStatus::PENDING)
            ->latest()
            ->get();
    }

    public function getActiveReleasedBorrowings(): Collection
    {
        return Borrowing::with(['borrower', 'items.tool'])
            ->whereIn('status', [BorrowStatus::RELEASED, BorrowStatus::OVERDUE])
            ->latest()
            ->get();
    }

    public function getOverdueBorrowings(): Collection
    {
        return Borrowing::with(['borrower', 'items.tool'])
            ->where(function ($q) {
                $q->where('status', BorrowStatus::OVERDUE)
                  ->orWhere(function ($sub) {
                      $sub->where('status', BorrowStatus::RELEASED)
                          ->where('expected_return_date', '<', now());
                  });
            })
            ->latest()
            ->get();
    }

    public function getFilteredBorrowings(?int $borrowerId, ?string $status, ?string $search, int $perPage = 15): LengthAwarePaginator
    {
        $query = Borrowing::with(['borrower', 'custodian', 'items.tool'])->latest();

        if ($borrowerId) {
            $query->where('borrower_id', $borrowerId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('borrow_code', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhereHas('borrower', function ($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('id_number', 'like', "%{$search}%");
                  });
            });
        }

        return $query->paginate($perPage);
    }

    public function createBorrowing(array $borrowingData, array $itemsData): Borrowing
    {
        return DB::transaction(function () use ($borrowingData, $itemsData) {
            $borrowing = Borrowing::create($borrowingData);

            foreach ($itemsData as $item) {
                BorrowingItem::create([
                    'borrowing_id' => $borrowing->id,
                    'tool_id' => $item['tool_id'],
                    'quantity_requested' => $item['quantity'],
                    'quantity_released' => 0,
                    'condition_upon_release' => $item['condition_upon_release'] ?? 'good',
                ]);
            }

            return $borrowing->load('items.tool');
        });
    }

    public function updateStatus(int $borrowingId, string $status, ?int $custodianId = null, ?string $reasonOrNotes = null): bool
    {
        $borrowing = Borrowing::find($borrowingId);
        if (!$borrowing) return false;

        $update = ['status' => $status];

        if ($custodianId) {
            $update['custodian_id'] = $custodianId;
        }

        if ($status === BorrowStatus::REJECTED->value) {
            $update['rejection_reason'] = $reasonOrNotes;
        } else if ($reasonOrNotes) {
            $update['notes'] = $reasonOrNotes;
        }

        if ($status === BorrowStatus::RELEASED->value) {
            $update['released_at'] = now();
        } elseif ($status === BorrowStatus::RETURNED->value) {
            $update['returned_at'] = now();
        }

        return $borrowing->update($update);
    }
}
