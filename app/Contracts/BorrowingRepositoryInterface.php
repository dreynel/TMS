<?php

namespace App\Contracts;

use App\Models\Borrowing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface BorrowingRepositoryInterface
{
    public function findById(int $id): ?Borrowing;
    public function findByBorrowCode(string $code): ?Borrowing;
    public function getPendingRequests(): Collection;
    public function getActiveReleasedBorrowings(): Collection;
    public function getOverdueBorrowings(): Collection;
    public function getFilteredBorrowings(?int $borrowerId, ?string $status, ?string $search, int $perPage = 15): LengthAwarePaginator;
    public function createBorrowing(array $borrowingData, array $itemsData): Borrowing;
    public function updateStatus(int $borrowingId, string $status, ?int $custodianId = null, ?string $reasonOrNotes = null): bool;
}
