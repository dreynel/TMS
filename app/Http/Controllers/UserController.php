<?php

namespace App\Http\Controllers;

use App\Contracts\UserRepositoryInterface;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepo) {}

    public function index()
    {
        $pendingUsers = $this->userRepo->getPendingBorrowers();
        $allUsers = $this->userRepo->getAllUsers();
        $roles = UserRole::cases();

        return view('users.index', compact('pendingUsers', 'allUsers', 'roles'));
    }

    public function approve(int $id)
    {
        $this->userRepo->approveUser($id);
        return back()->with('success', 'Borrower account approved successfully. User can now borrow tools.');
    }

    public function reject(int $id)
    {
        $this->userRepo->rejectUser($id);
        return back()->with('success', 'Pending registration rejected.');
    }

    public function updateRole(Request $request, int $id)
    {
        $request->validate(['role' => 'required|string']);
        $this->userRepo->updateUser($id, ['role' => $request->input('role'), 'is_approved' => true]);

        return back()->with('success', 'User role updated successfully.');
    }
}
