<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin users can access admin dashboard.
     */
    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    /**
     * Test that regular users cannot access admin dashboard.
     */
    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }

    /**
     * Test that unauthenticated users are redirected to login.
     */
    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /**
     * Test that admin users can access admin locations.
     */
    public function test_admin_user_can_access_admin_locations(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $response = $this->actingAs($admin)->get('/admin/locations');

        $response->assertStatus(200);
    }

    /**
     * Test that regular users cannot access admin locations.
     */
    public function test_regular_user_cannot_access_admin_locations(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
        ]);

        $response = $this->actingAs($user)->get('/admin/locations');

        $response->assertStatus(403);
    }
}
