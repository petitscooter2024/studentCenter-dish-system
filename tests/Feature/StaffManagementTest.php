<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_and_delete_staff(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Create staff account
        $this->post('/admin/staff', [
            'name' => 'Test Staff',
            'email' => 'staff@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'staff@example.com',
            'role' => 'staff',
        ]);

        $staff = User::where('email', 'staff@example.com')->firstOrFail();

        // Delete staff account
        $this->delete('/admin/staff/' . $staff->id);

        $this->assertDatabaseMissing('users', [
            'email' => 'staff@example.com',
        ]);
    }
}
