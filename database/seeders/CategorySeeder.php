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
                'description' => 'Breakfast meals and dishes.',
            ],
            [
                'name' => 'Main Meals',
                'description' => 'Lunch and dinner meals.',
            ],
            [
                'name' => 'Snacks',
                'description' => 'Light meals and snacks.',
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks and refreshments.',
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet dishes and desserts.',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}