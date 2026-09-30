<?php

namespace App\Services;

use App\Contracts\ToolRepositoryInterface;
use App\Models\Category;
use App\Models\Tool;

class InventoryService
{
    public function __construct(protected ToolRepositoryInterface $toolRepo) {}

    public function generateAssetCode(int $categoryId): string
    {
        $category = Category::find($categoryId);
        $codePrefix = $category ? strtoupper($category->code) : 'TOOL';

        $index = Tool::where('category_id', $categoryId)->count() + 1;
        do {
            $code = 'BIND-' . $codePrefix . '-' . str_pad((string) $index, 3, '0', STR_PAD_LEFT);
            $index++;
        } while (Tool::where('asset_code', $code)->exists());

        return $code;
    }

    public function addTool(array $data): Tool
    {
        if (empty($data['asset_code'])) {
            $data['asset_code'] = $this->generateAssetCode($data['category_id']);
        }
        $data['available_qty'] = $data['total_qty'];
        return $this->toolRepo->createTool($data);
    }
}
