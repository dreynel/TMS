<?php

namespace App\Repositories;

use App\Contracts\ToolRepositoryInterface;
use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ToolRepository implements ToolRepositoryInterface
{
    public function findById(int $id): ?Tool
    {
        return Tool::with(['category', 'logs.user'])->find($id);
    }

    public function findByAssetCode(string $code): ?Tool
    {
        return Tool::where('asset_code', $code)->first();
    }

    public function getAvailableTools(): Collection
    {
        return Tool::where('status', ToolStatus::AVAILABLE)
            ->where('available_qty', '>', 0)
            ->with('category')
            ->get();
    }

    public function getFilteredTools(?string $search, ?int $categoryId, ?string $status, ?string $condition, int $perPage = 15): LengthAwarePaginator
    {
        $query = Tool::with('category')->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('asset_code', 'like', "%{$search}%")
                  ->orWhere('brand_model', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($condition)) {
            $query->where('condition', $condition);
        }

        return $query->paginate($perPage);
    }

    public function createTool(array $data): Tool
    {
        $tool = Tool::create($data);

        ToolLog::create([
            'tool_id' => $tool->id,
            'user_id' => Auth::id(),
            'action' => 'created',
            'new_condition' => $tool->condition,
            'new_status' => $tool->status,
            'remarks' => 'Initial asset entry into BIND-Tech Inventory',
        ]);

        return $tool;
    }

    public function updateTool(int $id, array $data): bool
    {
        $tool = Tool::find($id);
        if (!$tool) return false;

        $prevCondition = $tool->condition;
        $prevStatus = $tool->status;

        $updated = $tool->update($data);

        if ($updated && ($prevCondition !== $tool->condition || $prevStatus !== $tool->status)) {
            ToolLog::create([
                'tool_id' => $tool->id,
                'user_id' => Auth::id(),
                'action' => 'updated',
                'previous_condition' => $prevCondition,
                'new_condition' => $tool->condition,
                'previous_status' => $prevStatus,
                'new_status' => $tool->status,
                'remarks' => 'Tool details / status updated',
            ]);
        }

        return $updated;
    }

    public function updateStockAndCondition(int $toolId, int $availableQtyDelta, ?string $newStatus = null, ?string $newCondition = null): Tool
    {
        $tool = Tool::findOrFail($toolId);
        $prevStatus = $tool->status;
        $prevCondition = $tool->condition;

        $tool->available_qty = max(0, min($tool->total_qty, $tool->available_qty + $availableQtyDelta));

        if ($newStatus) {
            $tool->status = ToolStatus::from($newStatus);
        } else {
            // Auto update status based on stock
            if ($tool->available_qty === 0 && $tool->total_qty > 0) {
                $tool->status = ToolStatus::IN_USE;
            } elseif ($tool->available_qty > 0 && $tool->status === ToolStatus::IN_USE) {
                $tool->status = ToolStatus::AVAILABLE;
            }
        }

        if ($newCondition) {
            $tool->condition = ToolCondition::from($newCondition);
        }

        $tool->save();

        if ($prevStatus !== $tool->status || $prevCondition !== $tool->condition) {
            ToolLog::create([
                'tool_id' => $tool->id,
                'user_id' => Auth::id(),
                'action' => 'stock_condition_changed',
                'previous_condition' => $prevCondition,
                'new_condition' => $tool->condition,
                'previous_status' => $prevStatus,
                'new_status' => $tool->status,
                'remarks' => "Qty delta: {$availableQtyDelta}, Current available: {$tool->available_qty}",
            ]);
        }

        return $tool;
    }

    public function deleteTool(int $id): bool
    {
        $tool = Tool::find($id);
        return $tool ? $tool->delete() : false;
    }
}
