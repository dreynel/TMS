<?php

namespace App\Enums;

enum BorrowStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case RELEASED = 'released';
    case RETURNED = 'returned';
    case OVERDUE = 'overdue';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending Custodian Review',
            self::APPROVED => 'Approved (Awaiting Pickup)',
            self::REJECTED => 'Request Rejected',
            self::RELEASED => 'Released / Active Borrow',
            self::RETURNED => 'Returned & Restocked',
            self::OVERDUE => 'Overdue Item',
            self::CANCELLED => 'Request Cancelled',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::PENDING => 'bg-amber-100 text-amber-800 border-amber-300',
            self::APPROVED => 'bg-indigo-100 text-indigo-800 border-indigo-300',
            self::REJECTED => 'bg-rose-100 text-rose-800 border-rose-300',
            self::RELEASED => 'bg-blue-100 text-blue-800 border-blue-300',
            self::RETURNED => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            self::OVERDUE => 'bg-red-100 text-red-800 border-red-300 animate-pulse',
            self::CANCELLED => 'bg-gray-100 text-gray-800 border-gray-300',
        };
    }
}
