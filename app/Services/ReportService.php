<?php

namespace App\Services;

use App\Enums\BorrowStatus;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use App\Models\ToolLog;

class ReportService
{
    /**
     * Tool Inventory Report Data
     */
    public function getInventoryReport(?int $categoryId = null, ?string $status = null, ?string $condition = null)
    {
        $query = Tool::with('category')->latest();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($condition) {
            $query->where('condition', $condition);
        }

        return $query->get();
    }

    /**
     * Borrowing History Report Data
     */
    public function getBorrowingHistoryReport(?string $startDate = null, ?string $endDate = null, ?string $status = null)
    {
        $query = Borrowing::with(['borrower', 'custodian', 'items.tool.category'])->latest();

        if ($startDate && $endDate) {
            $query->whereBetween('request_date', [$startDate, $endDate]);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * Overdue Items Report Data
     */
    public function getOverdueReport()
    {
        return Borrowing::with(['borrower', 'items.tool'])
            ->where(function ($q) {
                $q->where('status', BorrowStatus::OVERDUE->value)
                  ->orWhere(function ($sub) {
                      $sub->where('status', BorrowStatus::RELEASED->value)
                          ->where('expected_return_date', '<', now());
                  });
            })
            ->latest()
            ->get();
    }

    /**
     * Tool Condition & Audit Log Report Data
     */
    public function getConditionReport()
    {
        return ToolLog::with(['tool.category', 'user'])
            ->latest()
            ->get();
    }
}
