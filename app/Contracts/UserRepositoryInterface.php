<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email): ?User;
    public function getPendingBorrowers(): Collection;
    public function getAllUsers(): Collection;
    public function approveUser(int $userId): bool;
    public function rejectUser(int $userId): bool;
    public function createUser(array $data): User;
    public function updateUser(int $id, array $data): bool;
}
