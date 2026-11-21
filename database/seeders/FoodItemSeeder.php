<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\FoodItem;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {
        $foodItems = [
            [
                'category' => 'Breakfast',
                'name' => 'Tea and Bread',
                'description' => 'Hot tea served with fresh bread.',
            ],
            [
                'category' => 'Breakfast',
                'name' => 'Mandazi',
                'description' => 'Freshly prepared Kenyan mandazi.',
            ],
            [
                'category' => 'Main Meals',
                'name' => 'Pilau',
                'description' => 'Spiced rice served with beef.',
            ],
            [
                'category' => 'Main Meals',
                'name' => 'Ugali and Sukuma Wiki',
                'description' => 'Ugali served with fried sukuma wiki.',
            ],
            [
                'category' => 'Main Meals',
                'name' => 'Rice and Beans',
                'description' => 'Rice served with cooked beans.',
            ],
            [
                'category' => 'Snacks',
                'name' => 'Samosa',
                'description' => 'Crispy pastry with a savoury filling.',
            ],
            [
                'category' => 'Beverages',
                'name' => 'Fresh Juice',
                'description' => 'Freshly prepared fruit juice.',
            ],
            [
                'category' => 'Desserts',
                'name' => 'Fruit Salad',
                'description' => 'A mixture of fresh seasonal fruits.',
            ],
        ];

        foreach ($foodItems as $item) {
            $category = Category::where('name', $item['category'])->firstOrFail();

            FoodItem::firstOrCreate(
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                ],
                [
                    'description' => $item['description'],
                ]
            );
        }
    }
}