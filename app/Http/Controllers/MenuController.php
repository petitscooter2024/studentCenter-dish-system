<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\WeeklyMenu;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $today = today()->dayOfWeekIso;

        $dayName = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ][$today];

        $menu = null;
        $dailyMenu = null;
        $itemsByCategory = collect();

        if ($today !== 7) {
            $menu = WeeklyMenu::with([
                'weeklyMenuItems.foodItem.category',
            ])
                ->where('day_of_week', $today)
                ->where('is_active', true)
                ->first();

            if ($menu) {
                $dailyMenu = DailyMenu::firstOrCreate(
                    [
                        'menu_date' => today()->toDateString(),
                    ],
                    [
                        'title' => $menu->title,
                        'weekly_menu_id' => $menu->id,
                    ]
                );

                if ($dailyMenu->weekly_menu_id !== $menu->id) {
                    $dailyMenu->update([
                        'title' => $menu->title,
                        'weekly_menu_id' => $menu->id,
                    ]);
                }

                foreach ($menu->weeklyMenuItems as $weeklyItem) {
                    $dailyMenu->dailyMenuItems()->firstOrCreate(
                        [
                            'food_item_id' => $weeklyItem->food_item_id,
                        ],
                        [
                            'price' => $weeklyItem->price,
                            'is_available' => $weeklyItem->is_available,
                            'is_visible' => true,
                            'availability_override' => null,
                        ]
                    );
                }

                $dailyItems = $dailyMenu->dailyMenuItems()
                    ->where('is_visible', true)
                    ->with('foodItem.category')
                    ->get();

                $itemsByCategory = $dailyItems
                    ->sortBy(function ($item) {
                        return $item->foodItem?->category?->name ?? 'Uncategorized';
                    })
                    ->groupBy(function ($item) {
                        return $item->foodItem?->category?->name ?? 'Uncategorized';
                    });
            }
        }

        return view('menu.index', compact(
            'menu',
            'dailyMenu',
            'dayName',
            'itemsByCategory'
        ));
    }
}
