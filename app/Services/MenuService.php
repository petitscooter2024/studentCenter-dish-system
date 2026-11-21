<?php

namespace App\Services;

use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use Illuminate\Support\Facades\DB;

class MenuService
{
    public function getMenuForDate(string $date): ?DailyMenu
    {
        return DailyMenu::with([
            'dailyMenuItems.foodItem.category',
            'dailyMenuItems.ratings',
        ])
        ->whereDate('menu_date', $date)
        ->first();
    }

    public function createMenu(array $data): DailyMenu
    {
        return DailyMenu::updateOrCreate(
            ['menu_date' => $data['menu_date']],
            ['title' => $data['title'] ?? null]
        );
    }

    public function addDish(
        DailyMenu $menu,
        array $data
    ): DailyMenuItem {
        return DailyMenuItem::updateOrCreate(
            [
                'daily_menu_id' => $menu->id,
                'food_item_id' => $data['food_item_id'],
            ],
            [
                'price' => $data['price'],
                'is_available' => $data['is_available'] ?? true,
            ]
        );
    }

    public function removeDish(DailyMenuItem $item): void
    {
        DB::transaction(function () use ($item) {
            $item->delete();
        });
    }
}
