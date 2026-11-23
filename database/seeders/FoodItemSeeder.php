<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Database\Seeder;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {
        $foods = [
            [
                'category' => 'Breakfast',
                'name' => 'Tea and Bread',
                'description' => 'Hot tea served with fresh bread.',
                'image' => '/images/tea-and-bread.jpg',
            ],
            [
                'category' => 'Breakfast',
                'name' => 'Mandazi',
                'description' => 'Freshly prepared mandazi.',
                'image' => '/images/mandazi.jpg',
            ],
            [
                'category' => 'Main Dishes',
                'name' => 'Pilau',
                'description' => 'Spiced rice prepared with traditional seasoning.',
                'image' => '/images/pilau.jpg',
            ],
            [
                'category' => 'Sides',
                'name' => 'Ugali and Sukuma Wiki',
                'description' => 'Ugali served with cooked sukuma wiki.',
                'image' => '/images/ugali-and-sukuma-wiki.jpg',
            ],
            [
                'category' => 'Sides',
                'name' => 'Rice and Beans',
                'description' => 'Rice served with beans.',
                'image' => '/images/rice-and-beans.jpg',
            ],
            [
                'category' => 'Snacks',
                'name' => 'Samosa',
                'description' => 'Crispy savoury samosas.',
                'image' => '/images/samosa.jpg',
            ],
            [
                'category' => 'Beverages',
                'name' => 'Fresh Juice',
                'description' => 'Freshly prepared fruit juice.',
                'image' => '/images/fresh-juice.jpg',
            ],
            [
                'category' => 'Desserts',
                'name' => 'Fruit Salad',
                'description' => 'A fresh mix of seasonal fruits.',
                'image' => '/images/fruit-salad.jpg',
            ],
        ];

        foreach ($foods as $food) {
            $category = Category::where('name', $food['category'])->firstOrFail();

            FoodItem::updateOrCreate(
                ['name' => $food['name']],
                [
                    'category_id' => $category->id,
                    'description' => $food['description'],
                    'image' => $food['image'],
                ]
            );
        }
    }
}
