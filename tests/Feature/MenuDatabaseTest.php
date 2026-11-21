<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DailyMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuDatabaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_database_relationships_work(): void
    {
        $this->seed();

        $category = Category::where('name', 'Breakfast')->firstOrFail();

        $this->assertGreaterThan(
            0,
            $category->foodItems()->count()
        );

        $menu = DailyMenu::with(
            'dailyMenuItems.foodItem.category'
        )->whereDate('menu_date', today())->firstOrFail();

        $this->assertCount(8, $menu->dailyMenuItems);

        foreach ($menu->dailyMenuItems as $menuItem) {
            $this->assertNotNull($menuItem->foodItem);
            $this->assertNotNull($menuItem->foodItem->category);
        }
    }
}
