<?php

namespace App\Models;

use App\Enums\ToolCondition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'borrowing_id',
        'tool_id',
        'quantity_requested',
        'quantity_released',
        'condition_upon_release',
        'condition_upon_return',
        'return_notes',
    ];

    protected $casts = [
        'condition_upon_release' => ToolCondition::class,
        'condition_upon_return' => ToolCondition::class,
        'quantity_requested' => 'integer',
        'quantity_released' => 'integer',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
