<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\WeeklyMenu;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $dayNumber = today()->dayOfWeekIso;

        $dayName = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ][$dayNumber];

        $weeklyMenu = null;
        $dailyMenu = null;

        if ($dayNumber !== 7) {
            $weeklyMenu = WeeklyMenu::with([
                'weeklyMenuItems.foodItem.category',
            ])
                ->where('day_of_week', $dayNumber)
                ->where('is_active', true)
                ->first();

            if ($weeklyMenu) {
                $dailyMenu = DailyMenu::firstOrCreate(
                    [
                        'menu_date' => today()->toDateString(),
                    ],
                    [
                        'title' => $weeklyMenu->title,
                        'weekly_menu_id' => $weeklyMenu->id,
                    ]
                );

                if ($dailyMenu->weekly_menu_id !== $weeklyMenu->id) {
                    $dailyMenu->update([
                        'title' => $weeklyMenu->title,
                        'weekly_menu_id' => $weeklyMenu->id,
                    ]);
                }

                foreach ($weeklyMenu->weeklyMenuItems as $weeklyItem) {
                    $dailyMenu->dailyMenuItems()->firstOrCreate(
                        [
                            'food_item_id' => $weeklyItem->food_item_id,
                        ],
                        [
                            'price' => $weeklyItem->price,
                            'is_available' => $weeklyItem->is_available,
                            'availability_override' => null,
                        ]
                    );
                }

                $dailyMenu->load([
                    'dailyMenuItems.foodItem.category',
                ]);
            }
        }

        return view('home', compact(
            'weeklyMenu',
            'dailyMenu',
            'dayName'
        ));
    }
}
