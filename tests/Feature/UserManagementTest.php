<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
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

    public function test_custodian_or_admin_can_approve_pending_borrower()
    {
        $this->assertFalse($this->pendingStudent->is_approved);

        $response = $this->actingAs($this->custodian)->post(route('users.approve', $this->pendingStudent->id));
        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->pendingStudent->refresh();
        $this->assertTrue($this->pendingStudent->is_approved);
    }

    public function test_custodian_or_admin_can_reject_pending_borrower()
    {
        $newUser = User::create([
            'name' => 'Rejected Applicant',
            'email' => 'reject@isatu.edu.ph',
            'password' => 'secret123',
            'role' => UserRole::BORROWER->value,
            'is_approved' => false,
        ]);

        $response = $this->actingAs($this->custodian)->post(route('users.reject', $newUser->id));
        $response->assertRedirect();

        $this->assertDatabaseMissing('users', ['id' => $newUser->id]);
    }

    public function test_only_admin_can_update_user_roles()
    {
        // Custodian cannot change roles (403 forbidden)
        $custodianAttempt = $this->actingAs($this->custodian)->put(route('users.update-role', $this->student->id), [
            'role' => UserRole::CUSTODIAN->value
        ]);
        $custodianAttempt->assertStatus(403);

        // Admin can change role
        $adminAttempt = $this->actingAs($this->admin)->put(route('users.update-role', $this->student->id), [
            'role' => UserRole::CUSTODIAN->value
        ]);
        $adminAttempt->assertRedirect();
        $adminAttempt->assertSessionHas('success');

        $this->student->refresh();
        $this->assertEquals(UserRole::CUSTODIAN, $this->student->role);
    }

    public function test_admin_cannot_demote_own_account()
    {
        $response = $this->actingAs($this->admin)->put(route('users.update-role', $this->admin->id), [
            'role' => UserRole::BORROWER->value
        ]);

        $response->assertSessionHas('error');
        $this->admin->refresh();
        $this->assertEquals(UserRole::ADMIN, $this->admin->role);
    }
}
