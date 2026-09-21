<?php

namespace App\Models;

use App\Enums\BorrowStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrow_code',
        'borrower_id',
        'custodian_id',
        'purpose',
        'request_date',
        'expected_return_date',
        'released_at',
        'returned_at',
        'status',
        'notes',
        'rejection_reason',
    ];

    protected $casts = [
        'request_date' => 'datetime',
        'expected_return_date' => 'datetime',
        'released_at' => 'datetime',
        'returned_at' => 'datetime',
        'status' => BorrowStatus::class,
    ];

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function custodian()
    {
        return $this->belongsTo(User::class, 'custodian_id');
    }

    public function items()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function isOverdue(): bool
    {
        if ($this->status === BorrowStatus::RELEASED && now()->greaterThan($this->expected_return_date)) {
            return true;
        }
        return $this->status === BorrowStatus::OVERDUE;
    }
}
