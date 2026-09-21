<?php

namespace App\Http\Controllers;

use App\Contracts\BorrowingRepositoryInterface;
use App\Contracts\ToolRepositoryInterface;
use App\Contracts\UserRepositoryInterface;
use App\Enums\BorrowStatus;
use App\Enums\ToolStatus;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected ToolRepositoryInterface $toolRepo,
        protected BorrowingRepositoryInterface $borrowingRepo,
        protected UserRepositoryInterface $userRepo,
        protected BorrowingService $borrowingService
    ) {}

    public function index()
    {
        // Auto check overdue items
        $this->borrowingService->checkAndUpdateOverdue();

        $user = Auth::user();

        $stats = [
            'total_tools' => \App\Models\Tool::sum('total_qty'),
            'available_tools' => \App\Models\Tool::sum('available_qty'),
            'pending_requests' => \App\Models\Borrowing::where('status', BorrowStatus::PENDING)->count(),
            'active_released' => \App\Models\Borrowing::where('status', BorrowStatus::RELEASED)->count(),
            'overdue_count' => \App\Models\Borrowing::where('status', BorrowStatus::OVERDUE)->count(),
            'damaged_tools' => \App\Models\Tool::where('status', ToolStatus::DAMAGED)->count(),
            'pending_users' => \App\Models\User::where('is_approved', false)->count(),
        ];

        $pendingBorrowings = $this->borrowingRepo->getPendingRequests()->take(5);
        $overdueBorrowings = $this->borrowingRepo->getOverdueBorrowings()->take(5);
        $userBorrowings = $user->isBorrower()
            ? \App\Models\Borrowing::where('borrower_id', $user->id)->with('items.tool')->latest()->take(5)->get()
            : collect();

        return view('dashboard.index', compact('user', 'stats', 'pendingBorrowings', 'overdueBorrowings', 'userBorrowings'));
    }
}
