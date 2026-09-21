<?php

namespace App\Models;

use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_code',
        'name',
        'category_id',
        'brand_model',
        'serial_number',
        'location_storage',
        'total_qty',
        'available_qty',
        'status',
        'condition',
        'image_path',
        'description',
    ];

    protected $casts = [
        'status' => ToolStatus::class,
        'condition' => ToolCondition::class,
        'total_qty' => 'integer',
        'available_qty' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowingItems()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function logs()
    {
        return $this->hasMany(ToolLog::class)->latest();
    }

    public function isAvailable(int $requestedQty = 1): bool
    {
        return $this->status === ToolStatus::AVAILABLE && $this->available_qty >= $requestedQty;
    }
}
