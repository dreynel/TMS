<?php

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getPendingBorrowers(): Collection
    {
        return User::where('role', UserRole::BORROWER)
            ->where('is_approved', false)
            ->latest()
            ->get();
    }

    public function getAllUsers(): Collection
    {
        return User::latest()->get();
    }

    public function approveUser(int $userId): bool
    {
        $user = User::find($userId);
        if ($user) {
            return $user->update(['is_approved' => true]);
        }
        return false;
    }

    public function rejectUser(int $userId): bool
    {
        $user = User::find($userId);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(int $id, array $data): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }
}
