<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['auth', 'role:admin'])
            ->get('/test-admin-access', fn () => response('Admin access granted'));

        Route::middleware(['auth', 'role:staff'])
            ->get('/test-staff-access', fn () => response('Staff access granted'));
    }

    public function test_admin_can_access_admin_area(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get('/test-admin-access')
            ->assertOk()
            ->assertSee('Admin access granted');
    }

    public function test_staff_cannot_access_admin_area(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $this->actingAs($staff)
            ->get('/test-admin-access')
            ->assertForbidden();
    }

    public function test_staff_can_access_staff_area(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
        ]);

        $this->actingAs($staff)
            ->get('/test-staff-access')
            ->assertOk()
            ->assertSee('Staff access granted');
    }

    public function test_guest_cannot_access_protected_areas(): void
    {
        $this->get('/test-admin-access')
            ->assertRedirect(route('login'));

        $this->get('/test-staff-access')
            ->assertRedirect(route('login'));
    }
}
