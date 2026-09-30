<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthLoginRolesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_admin_can_login_with_username()
    {
        $response = $this->post('/login', [
            'login' => 'admin',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isAdmin());
    }

    public function test_custodian_can_login_with_username()
    {
        $response = $this->post('/login', [
            'login' => 'custodian',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isCustodian());
    }

    public function test_borrower_can_login_with_username()
    {
        $response = $this->post('/login', [
            'login' => 'student',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isBorrower());
    }

    public function test_can_login_with_full_email()
    {
        $response = $this->post('/login', [
            'login' => 'admin@isatu.edu.ph',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('admin@isatu.edu.ph', auth()->user()->email);
    }

    public function test_admin_and_custodian_can_access_tools_create_but_borrower_is_forbidden()
    {
        $admin = User::where('email', 'admin@isatu.edu.ph')->first();
        $custodian = User::where('email', 'custodian@isatu.edu.ph')->first();
        $student = User::where('email', 'student@isatu.edu.ph')->first();

        // Admin can access
        $this->actingAs($admin)->get('/tools/create')->assertStatus(200);

        // Custodian can access
        $this->actingAs($custodian)->get('/tools/create')->assertStatus(200);

        // Borrower is forbidden (403)
        $this->actingAs($student)->get('/tools/create')->assertStatus(403);
    }
}
