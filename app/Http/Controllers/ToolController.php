<?php

namespace App\Http\Controllers;

use App\Contracts\ToolRepositoryInterface;
use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Models\Category;
use App\Services\InventoryService;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    public function __construct(
        protected ToolRepositoryInterface $toolRepo,
        protected InventoryService $inventoryService
    ) {}

    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $condition = $request->query('condition');

        $tools = $this->toolRepo->getFilteredTools($search, $categoryId, $status, $condition, 12);
        $categories = Category::all();
        $statuses = ToolStatus::cases();
        $conditions = ToolCondition::cases();

        return view('tools.index', compact('tools', 'categories', 'statuses', 'conditions', 'search', 'categoryId', 'status', 'condition'));
    }

    public function create()
    {
        $categories = Category::all();
        $statuses = ToolStatus::cases();
        $conditions = ToolCondition::cases();

        return view('tools.create', compact('categories', 'statuses', 'conditions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_code' => 'nullable|string|unique:tools,asset_code',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'location_storage' => 'required|string|max:255',
            'total_qty' => 'required|integer|min:1',
            'status' => 'required|string',
            'condition' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tools', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $tool = $this->inventoryService->addTool($validated);

        return redirect()->route('tools.show', $tool->id)->with('success', "Tool '{$tool->name}' created successfully with asset code [{$tool->asset_code}].");
    }

    public function show(int $id)
    {
        $tool = $this->toolRepo->findById($id);
        if (!$tool) {
            abort(404, 'Tool not found.');
        }

        return view('tools.show', compact('tool'));
    }

    public function edit(int $id)
    {
        $tool = $this->toolRepo->findById($id);
        if (!$tool) {
            abort(404, 'Tool not found.');
        }

        $categories = Category::all();
        $statuses = ToolStatus::cases();
        $conditions = ToolCondition::cases();

        return view('tools.edit', compact('tool', 'categories', 'statuses', 'conditions'));
    }

    public function update(Request $request, int $id)
    {
        $tool = $this->toolRepo->findById($id);
        if (!$tool) {
            abort(404, 'Tool not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_model' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'location_storage' => 'required|string|max:255',
            'total_qty' => 'required|integer|min:1',
            'status' => 'required|string',
            'condition' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tools', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        // recalculate available_qty if total_qty changed
        if ($validated['total_qty'] !== $tool->total_qty) {
            $diff = $validated['total_qty'] - $tool->total_qty;
            $validated['available_qty'] = max(0, $tool->available_qty + $diff);
        }

        $this->toolRepo->updateTool($id, $validated);

        return redirect()->route('tools.show', $id)->with('success', 'Tool record updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->toolRepo->deleteTool($id);
        return redirect()->route('tools.index')->with('success', 'Tool removed from inventory.');
    }
}
