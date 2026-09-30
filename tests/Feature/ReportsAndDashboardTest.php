<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportsAndDashboardTest extends TestCase
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

    public function test_dashboard_renders_successfully_for_all_roles()
    {
        $this->actingAs($this->admin)->get(route('dashboard'))->assertStatus(200);
        $this->actingAs($this->custodian)->get(route('dashboard'))->assertStatus(200);
        $this->actingAs($this->student)->get(route('dashboard'))->assertStatus(200);
    }

    public function test_inventory_report_and_print_view_render_successfully()
    {
        $this->actingAs($this->custodian)->get(route('reports.inventory'))->assertStatus(200);
        $this->actingAs($this->custodian)->get(route('reports.inventory', ['print' => 1]))->assertStatus(200);
    }

    public function test_borrowing_history_report_and_print_view_render_successfully()
    {
        $this->actingAs($this->custodian)->get(route('reports.borrowing_history'))->assertStatus(200);
        $this->actingAs($this->custodian)->get(route('reports.borrowing_history', ['print' => 1]))->assertStatus(200);
    }

    public function test_overdue_report_and_print_view_render_successfully()
    {
        $this->actingAs($this->custodian)->get(route('reports.overdue'))->assertStatus(200);
        $this->actingAs($this->custodian)->get(route('reports.overdue', ['print' => 1]))->assertStatus(200);
    }

    public function test_condition_audit_report_and_print_view_render_successfully()
    {
        $this->actingAs($this->custodian)->get(route('reports.condition_audit'))->assertStatus(200);
        $this->actingAs($this->custodian)->get(route('reports.condition_audit', ['print' => 1]))->assertStatus(200);
    }

    public function test_overdue_artisan_command_executes_successfully()
    {
        $this->artisan('borrowings:check-overdue')
            ->expectsOutputToContain('Scan completed')
            ->assertExitCode(0);
    }
}
