<?php

namespace Tests\Feature;

use App\Enums\BorrowStatus;
use App\Enums\ToolCondition;
use App\Enums\ToolStatus;
use App\Models\Borrowing;
use App\Models\Tool;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingEndToEndTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $custodian;
    protected User $student;
    protected User $pendingStudent;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->admin = User::where('email', 'admin@isatu.edu.ph')->first();
        $this->custodian = User::where('email', 'custodian@isatu.edu.ph')->first();
        $this->student = User::where('email', 'student@isatu.edu.ph')->first();
        $this->pendingStudent = User::where('email', 'pending.student@isatu.edu.ph')->first();
    }

    public function test_full_borrowing_lifecycle_from_request_to_restock()
    {
        // 1. Student requests a tool
        $tool = Tool::where('asset_code', 'BIND-HT-001')->first();
        $initialStock = $tool->available_qty;

        $response = $this->actingAs($this->student)->post(route('borrowings.store'), [
            'purpose' => 'E2E Testing Circuit Wiring Work',
            'request_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_return_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'tools' => [
                ['tool_id' => $tool->id, 'quantity' => 2]
            ]
        ]);

        $response->assertSessionHasNoErrors();
        $borrowing = Borrowing::where('borrower_id', $this->student->id)
            ->where('purpose', 'E2E Testing Circuit Wiring Work')
            ->first();

        $this->assertNotNull($borrowing);
        $this->assertEquals(BorrowStatus::PENDING, $borrowing->status);

        // 2. Custodian approves the request
        $approveRes = $this->actingAs($this->custodian)->post(route('borrowings.approve', $borrowing->id), [
            'notes' => 'Approved for workshop bench 1'
        ]);
        $approveRes->assertRedirect(route('borrowings.show', $borrowing->id));
        $borrowing->refresh();
        $this->assertEquals(BorrowStatus::APPROVED, $borrowing->status);

        // 3. Custodian releases tools to borrower (stock decreases)
        $releaseRes = $this->actingAs($this->custodian)->post(route('borrowings.release', $borrowing->id));
        $releaseRes->assertRedirect(route('borrowings.show', $borrowing->id));

        $borrowing->refresh();
        $tool->refresh();
        $this->assertEquals(BorrowStatus::RELEASED, $borrowing->status);
        $this->assertEquals($initialStock - 2, $tool->available_qty);

        // 4. Custodian processes return with inspection
        $returnRes = $this->actingAs($this->custodian)->post(route('borrowings.return', $borrowing->id), [
            'notes' => 'Returned complete and clean',
            'items' => [
                $borrowing->items->first()->id => [
                    'condition' => ToolCondition::GOOD->value,
                    'notes' => 'Tested and verified operational',
                ]
            ]
        ]);

        $returnRes->assertRedirect(route('borrowings.show', $borrowing->id));
        $borrowing->refresh();
        $tool->refresh();

        $this->assertEquals(BorrowStatus::RETURNED, $borrowing->status);
        $this->assertEquals($initialStock, $tool->available_qty);
    }

    public function test_unapproved_borrower_is_blocked_from_submitting_requests()
    {
        $tool = Tool::first();

        $response = $this->actingAs($this->pendingStudent)->get(route('borrowings.create'));
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('error');

        $postResponse = $this->actingAs($this->pendingStudent)->post(route('borrowings.store'), [
            'purpose' => 'Unauthorized Request',
            'request_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_return_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'tools' => [
                ['tool_id' => $tool->id, 'quantity' => 1]
            ]
        ]);

        $postResponse->assertRedirect(route('dashboard'));
        $postResponse->assertSessionHas('error');
    }

    public function test_borrower_with_overdue_items_cannot_submit_new_request()
    {
        $userWithOverdue = User::where('email', 'carlos@isatu.edu.ph')->first();
        $this->assertTrue($userWithOverdue->hasOverdueBorrowings());

        $tool = Tool::first();

        $response = $this->actingAs($userWithOverdue)->post(route('borrowings.store'), [
            'purpose' => 'Trying to borrow while having overdue items',
            'request_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_return_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'tools' => [
                ['tool_id' => $tool->id, 'quantity' => 1]
            ]
        ]);

        $response->assertSessionHas('error');
    }

    public function test_insufficient_stock_prevents_request()
    {
        $tool = Tool::where('asset_code', 'BIND-MT-003')->first(); // Rigol Oscilloscope total 2, available 2

        $response = $this->actingAs($this->student)->post(route('borrowings.store'), [
            'purpose' => 'Excessive Qty Request',
            'request_date' => now()->addDay()->format('Y-m-d H:i:s'),
            'expected_return_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'tools' => [
                ['tool_id' => $tool->id, 'quantity' => 999]
            ]
        ]);

        $response->assertSessionHas('error');
    }

    public function test_borrower_cannot_view_other_users_borrowing()
    {
        $otherBorrowing = Borrowing::where('borrower_id', '!=', $this->student->id)->first();

        $response = $this->actingAs($this->student)->get(route('borrowings.show', $otherBorrowing->id));
        $response->assertStatus(403);
    }
}
