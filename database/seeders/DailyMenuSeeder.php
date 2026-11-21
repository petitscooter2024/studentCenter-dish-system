<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\FoodItem;

class DailyMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = DailyMenu::firstOrCreate(
            ['menu_date' => today()->toDateString()],
            ['title' => "Today's Cafeteria Menu"]
        );

        $menuItems = [
            ['name' => 'Tea and Bread', 'price' => 80],
            ['name' => 'Mandazi', 'price' => 30],
            ['name' => 'Pilau', 'price' => 180],
            ['name' => 'Ugali and Sukuma Wiki', 'price' => 100],
            ['name' => 'Rice and Beans', 'price' => 120],
            ['name' => 'Samosa', 'price' => 50],
            ['name' => 'Fresh Juice', 'price' => 100],
            ['name' => 'Fruit Salad', 'price' => 80],
        ];

        foreach ($menuItems as $item) {
            $food = FoodItem::where('name', $item['name'])->firstOrFail();

            DailyMenuItem::updateOrCreate(
                [
                    'daily_menu_id' => $menu->id,
                    'food_item_id' => $food->id,
                ],
                [
                    'price' => $item['price'],
                    'is_available' => true,
                ]
            );
        }
    }
}