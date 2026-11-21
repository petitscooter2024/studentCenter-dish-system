<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DailyMenu;
use App\Models\FoodItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_daily_menu_items(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $category = Category::create([
            'name' => 'Breakfast',
            'description' => 'Breakfast meals',
        ]);

        $food = FoodItem::create([
            'category_id' => $category->id,
            'name' => 'Tea and Bread',
            'description' => 'Tea served with bread',
        ]);

        $this->actingAs($admin);

        // Create a daily menu
        $this->post(route('admin.daily-menus.store'), [
            'menu_date' => '2026-11-22',
            'title' => 'Sunday Menu',
        ])->assertSessionHasNoErrors();

        $menu = DailyMenu::whereDate('menu_date', '2026-11-22')
            ->firstOrFail();

        // Add a dish
        $this->post(route('admin.daily-menus.items.store', $menu), [
            'food_item_id' => $food->id,
            'price' => 100,
            'is_available' => 1,
        ])->assertSessionHasNoErrors();

        $menuItem = $menu->dailyMenuItems()->firstOrFail();

        $this->assertDatabaseHas('daily_menu_items', [
            'id' => $menuItem->id,
            'price' => 100,
        ]);

        // Update price and availability
        $this->put(route('admin.menu-items.update', $menuItem), [
            'price' => 120,
            'is_available' => 0,
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('daily_menu_items', [
            'id' => $menuItem->id,
            'price' => 120,
            'is_available' => 0,
        ]);

        // Remove the dish
        $this->delete(route('admin.menu-items.destroy', $menuItem))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('daily_menu_items', [
            'id' => $menuItem->id,
        ]);
    }
}
