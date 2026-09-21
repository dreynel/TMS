<?php

namespace App\Http\Controllers;

use App\Contracts\BorrowingRepositoryInterface;
use App\Contracts\ToolRepositoryInterface;
use App\Enums\BorrowStatus;
use App\Enums\ToolCondition;
use App\Services\BorrowingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function __construct(
        protected BorrowingRepositoryInterface $borrowingRepo,
        protected ToolRepositoryInterface $toolRepo,
        protected BorrowingService $borrowingService
    ) {}

    public function index(Request $request)
    {
        $user = Auth::user();
        $borrowerId = $user->isBorrower() ? $user->id : null;
        $status = $request->query('status');
        $search = $request->query('search');

        $borrowings = $this->borrowingRepo->getFilteredBorrowings($borrowerId, $status, $search, 15);
        $statuses = BorrowStatus::cases();

        return view('borrowings.index', compact('borrowings', 'statuses', 'status', 'search'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->isBorrower() && !$user->is_approved) {
            return redirect()->route('dashboard')->with('error', 'Your borrower account is pending approval by BIND-Tech Tool Custodian/Admin.');
        }

        $availableTools = $this->toolRepo->getAvailableTools();
        return view('borrowings.create', compact('availableTools'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purpose' => 'required|string',
            'request_date' => 'required|date',
            'expected_return_date' => 'required|date|after_or_equal:request_date',
            'tools' => 'required|array|min:1',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.quantity' => 'required|integer|min:1',
        ]);

        $borrowerId = Auth::id();

        $result = $this->borrowingService->submitRequest(
            $borrowerId,
            $validated['purpose'],
            $validated['request_date'],
            $validated['expected_return_date'],
            $validated['tools']
        );

        if (!$result['success']) {
            return back()->with('error', $result['message'])->withInput();
        }

        return redirect()->route('borrowings.show', $result['borrowing']->id)->with('success', $result['message']);
    }

    public function show(int $id)
    {
        $borrowing = $this->borrowingRepo->findById($id);
        if (!$borrowing) {
            abort(404, 'Borrowing transaction not found.');
        }

        $user = Auth::user();
        if ($user->isBorrower() && $borrowing->borrower_id !== $user->id) {
            abort(403, 'Unauthorized access to borrowing record.');
        }

        $toolConditions = ToolCondition::cases();

        return view('borrowings.show', compact('borrowing', 'toolConditions'));
    }

    public function approve(Request $request, int $id)
    {
        $custodianId = Auth::id();
        $notes = $request->input('notes');

        $this->borrowingService->approveRequest($id, $custodianId, $notes);

        return redirect()->route('borrowings.show', $id)->with('success', 'Borrowing request approved. Ready for tool release.');
    }

    public function reject(Request $request, int $id)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $custodianId = Auth::id();

        $this->borrowingService->rejectRequest($id, $custodianId, $request->input('rejection_reason'));

        return redirect()->route('borrowings.show', $id)->with('success', 'Borrowing request rejected.');
    }

    public function release(int $id)
    {
        $custodianId = Auth::id();
        $result = $this->borrowingService->releaseTools($id, $custodianId);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('borrowings.show', $id)->with('success', $result['message']);
    }

    public function processReturn(Request $request, int $id)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.condition' => 'required|string',
            'items.*.notes' => 'nullable|string',
        ]);

        $custodianId = Auth::id();
        $result = $this->borrowingService->processReturn($id, $custodianId, $request->input('items'), $request->input('notes'));

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('borrowings.show', $id)->with('success', $result['message']);
    }
}
