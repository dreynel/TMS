<?php

namespace Tests\Feature;

use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Models\Category;
use App\Models\Tool;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ToolManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $custodian;
    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@isatu.edu.ph')->first();
        $this->custodian = User::where('email', 'custodian@isatu.edu.ph')->first();
        $this->student = User::where('email', 'student@isatu.edu.ph')->first();
    }

    public function test_custodian_can_create_tool_with_auto_generated_asset_code()
    {
        $category = Category::where('code', 'HT')->first();

        $response = $this->actingAs($this->custodian)->post(route('tools.store'), [
            'name' => 'Adjustable Spanner Wrench 10-Inch',
            'category_id' => $category->id,
            'brand_model' => 'Crescent 10IN',
            'serial_number' => 'CR-10-881',
            'location_storage' => 'Toolboard A - Row 3',
            'total_qty' => 5,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::NEW->value,
            'description' => 'Drop-forged alloy steel spanner with chrome finish.',
        ]);

        $tool = Tool::where('name', 'Adjustable Spanner Wrench 10-Inch')->first();
        $this->assertNotNull($tool);
        $this->assertStringStartsWith('BIND-HT-', $tool->asset_code);
        $this->assertEquals(5, $tool->available_qty);
        $response->assertRedirect(route('tools.show', $tool->id));
    }

    public function test_custodian_can_update_tool_details_and_quantity()
    {
        $tool = Tool::where('asset_code', 'BIND-HT-001')->first();

        $response = $this->actingAs($this->custodian)->put(route('tools.update', $tool->id), [
            'name' => 'Heavy Duty Claw Hammer 16oz (Updated)',
            'category_id' => $tool->category_id,
            'brand_model' => 'Stanley Pro Series',
            'serial_number' => $tool->serial_number,
            'location_storage' => 'Toolboard B - Position 14',
            'total_qty' => 12, // Increased from 10 to 12
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
            'description' => 'Updated specs for testing',
        ]);

        $response->assertRedirect(route('tools.show', $tool->id));
        $tool->refresh();

        $this->assertEquals('Heavy Duty Claw Hammer 16oz (Updated)', $tool->name);
        $this->assertEquals(12, $tool->total_qty);
        $this->assertEquals(12, $tool->available_qty);
    }

    public function test_cannot_delete_tool_with_active_borrowings()
    {
        // Tool BIND-MT-001 is actively borrowed in seeded data
        $tool = Tool::where('asset_code', 'BIND-MT-001')->first();
        $this->assertTrue($tool->hasActiveBorrowings());
        $this->assertFalse($tool->canBeDeleted());

        $response = $this->actingAs($this->admin)->delete(route('tools.destroy', $tool->id));
        $response->assertSessionHas('error');

        // Verify tool still exists
        $this->assertDatabaseHas('tools', ['id' => $tool->id]);
    }

    public function test_can_delete_unused_tool()
    {
        // Create an unused tool
        $category = Category::first();
        $tool = Tool::create([
            'asset_code' => 'BIND-TEST-DEL',
            'name' => 'Temporary Test Tool',
            'category_id' => $category->id,
            'total_qty' => 1,
            'available_qty' => 1,
            'status' => ToolStatus::AVAILABLE->value,
            'condition' => ToolCondition::GOOD->value,
        ]);

        $this->assertTrue($tool->canBeDeleted());

        $response = $this->actingAs($this->admin)->delete(route('tools.destroy', $tool->id));
        $response->assertRedirect(route('tools.index'));
        $this->assertDatabaseMissing('tools', ['id' => $tool->id]);
    }
}
