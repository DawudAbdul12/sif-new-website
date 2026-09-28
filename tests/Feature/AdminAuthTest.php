<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_loads(): void
    {
        $response = $this->get('/admin/login');

        $response->assertOk();
        $response->assertSee('Admin Login');
    }

    public function test_admin_can_login_and_access_dashboard(): void
    {
        User::factory()->create([
            'name' => 'GoldBod Admin',
            'email' => 'admin@goldbod.gov.gh',
            'password' => 'secret-password',
            'is_admin' => true,
        ]);

        $response = $this->post('/admin/login', [
            'email' => 'admin@goldbod.gov.gh',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'logged_in',
            'subject_type' => User::class,
            'subject_label' => 'GoldBod Admin',
            'causer_id' => User::where('email', 'admin@goldbod.gov.gh')->value('id'),
            'causer_email' => 'admin@goldbod.gov.gh',
            'request_method' => 'POST',
        ]);

        $this->get('/admin')->assertOk()->assertSee('Dashboard');
    }

    public function test_non_admin_user_cannot_login_to_admin(): void
    {
        User::factory()->create([
            'email' => 'editor@goldbod.gov.gh',
            'password' => 'secret-password',
            'is_admin' => false,
        ]);

        $response = $this->from('/admin/login')->post('/admin/login', [
            'email' => 'editor@goldbod.gov.gh',
            'password' => 'secret-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->assertDatabaseMissing('activity_logs', [
            'action' => 'logged_in',
            'subject_type' => User::class,
            'causer_email' => 'editor@goldbod.gov.gh',
        ]);
    }

    public function test_guest_is_redirected_to_admin_login(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'logged_out',
            'subject_type' => User::class,
            'subject_id' => $admin->id,
            'causer_id' => $admin->id,
            'request_method' => 'POST',
        ]);
    }

    public function test_auth_activity_is_visible_in_activity_log_screen(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        ActivityLog::create([
            'action' => 'logged_in',
            'subject_type' => User::class,
            'subject_id' => $admin->id,
            'subject_label' => $admin->name,
            'causer_id' => $admin->id,
            'causer_name' => $admin->name,
            'causer_email' => $admin->email,
            'request_method' => 'POST',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.activity-logs.index', ['action' => 'logged_in']))
            ->assertOk()
            ->assertSee('Logged In')
            ->assertSee('Login / Logout');
    }
}
