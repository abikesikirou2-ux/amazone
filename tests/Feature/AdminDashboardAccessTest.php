<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class AdminDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard_without_email_verification_and_is_redirected_to_admin(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.test',
            'role' => 'admin',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_client_unverified_is_redirected_to_verification_notice(): void
    {
        $client = User::factory()->create([
            'email' => 'client@example.test',
            'role' => 'client',
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($client)->get('/dashboard');

        $response->assertRedirect(route('verification.notice'));
    }
}
