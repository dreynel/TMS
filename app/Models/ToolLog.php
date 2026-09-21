<?php

namespace App\Models;

use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_id',
        'user_id',
        'action',
        'previous_condition',
        'new_condition',
        'previous_status',
        'new_status',
        'remarks',
    ];

    protected $casts = [
        'previous_condition' => ToolCondition::class,
        'new_condition' => ToolCondition::class,
        'previous_status' => ToolStatus::class,
        'new_status' => ToolStatus::class,
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
