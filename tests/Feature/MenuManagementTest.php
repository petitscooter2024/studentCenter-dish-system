<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\FoodItem;
use App\Models\User;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_weekly_menu_and_add_meals(): void
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

        $this->post(route('admin.weekly-menus.store'), [
            'day_of_week' => 1,
            'title' => 'Monday Menu',
        ])->assertSessionHasNoErrors();

        $menu = WeeklyMenu::where('day_of_week', 1)->firstOrFail();

        $this->post(route('admin.weekly-menus.items.store', $menu), [
            'items' => [
                [
                    'food_item_id' => $food->id,
                    'selected' => 1,
                    'price' => 100,
                ],
            ],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('weekly_menu_items', [
            'weekly_menu_id' => $menu->id,
            'food_item_id' => $food->id,
            'price' => 100,
            'is_available' => 1,
        ]);

        $menuItem = WeeklyMenuItem::where('weekly_menu_id', $menu->id)
            ->where('food_item_id', $food->id)
            ->firstOrFail();

        $this->delete(route('admin.weekly-menus.items.destroy', [
            $menu,
            $menuItem,
        ]))->assertSessionHasNoErrors();

        $this->assertDatabaseHas('weekly_menu_items', [
            'id' => $menuItem->id,
            'is_available' => 0,
        ]);
    }
}
