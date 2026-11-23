<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Breakfast',
                'description' => 'Breakfast meals and foods.',
            ],
            [
                'name' => 'Main Dishes',
                'description' => 'Main meals served at the cafeteria.',
            ],
            [
                'name' => 'Sides',
                'description' => 'Side dishes such as ugali, rice and chapati.',
            ],
            [
                'name' => 'Snacks',
                'description' => 'Snacks such as samosas and sausages.',
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks such as tea, water and juice.',
            ],
            [
                'name' => 'Desserts',
                'description' => 'Desserts such as fruit salad and yoghurt.',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
