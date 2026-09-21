<?php

namespace App\Contracts;

use App\Models\Tool;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ToolRepositoryInterface
{
    public function findById(int $id): ?Tool;
    public function findByAssetCode(string $code): ?Tool;
    public function getAvailableTools(): Collection;
    public function getFilteredTools(?string $search, ?int $categoryId, ?string $status, ?string $condition, int $perPage = 15): LengthAwarePaginator;
    public function createTool(array $data): Tool;
    public function updateTool(int $id, array $data): bool;
    public function updateStockAndCondition(int $toolId, int $availableQtyDelta, ?string $newStatus = null, ?string $newCondition = null): Tool;
    public function deleteTool(int $id): bool;
}
