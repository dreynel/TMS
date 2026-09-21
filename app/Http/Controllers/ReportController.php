<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(protected ReportService $reportService) {}

    public function index()
    {
        return view('reports.index');
    }

    public function inventory(Request $request)
    {
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $condition = $request->query('condition');
        $isPrint = $request->query('print', false);

        $tools = $this->reportService->getInventoryReport($categoryId, $status, $condition);
        $categories = Category::all();

        $view = $isPrint ? 'reports.print_inventory' : 'reports.inventory';
        return view($view, compact('tools', 'categories', 'categoryId', 'status', 'condition'));
    }

    public function borrowingHistory(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $status = $request->query('status');
        $isPrint = $request->query('print', false);

        $borrowings = $this->reportService->getBorrowingHistoryReport($startDate, $endDate, $status);

        $view = $isPrint ? 'reports.print_borrowing_history' : 'reports.borrowing_history';
        return view($view, compact('borrowings', 'startDate', 'endDate', 'status'));
    }

    public function overdue(Request $request)
    {
        $isPrint = $request->query('print', false);
        $overdues = $this->reportService->getOverdueReport();

        $view = $isPrint ? 'reports.print_overdue' : 'reports.overdue';
        return view($view, compact('overdues'));
    }

    public function conditionAudit(Request $request)
    {
        $isPrint = $request->query('print', false);
        $logs = $this->reportService->getConditionReport();

        $view = $isPrint ? 'reports.print_condition' : 'reports.condition';
        return view($view, compact('logs'));
    }
}
