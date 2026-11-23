
<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\FoodItem;
use App\Models\WeeklyMenu;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function dashboard(): View
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

        $totalDishes = FoodItem::count();

        return view('staff.dashboard', compact(
            'weeklyMenu',
            'dailyMenu',
            'dayName',
            'totalDishes'
        ));
    }

    public function updateAvailability(
        Request $request,
        DailyMenuItem $item
    ): RedirectResponse {
        $validated = $request->validate([
            'is_available' => ['required', 'boolean'],
        ]);

        $item->load('dailyMenu');

        if (
            !$item->dailyMenu ||
            !$item->dailyMenu->menu_date->isToday() ||
            today()->dayOfWeekIso === 7
        ) {
            abort(403, 'You can only update availability for today\'s menu.');
        }

        $weeklyMenu = WeeklyMenu::where(
            'day_of_week',
            today()->dayOfWeekIso
        )
            ->where('is_active', true)
            ->first();

        if (
            !$weeklyMenu ||
            $item->dailyMenu->weekly_menu_id !== $weeklyMenu->id
        ) {
            abort(403, 'This meal does not belong to today\'s active menu.');
        }

        $availability = (bool) $validated['is_available'];

        $item->update([
            'is_available' => $availability,
            'availability_override' => $availability,
        ]);

        return redirect()
            ->route('staff.dashboard')
            ->with('success', 'Meal availability updated successfully.');
    }
}
