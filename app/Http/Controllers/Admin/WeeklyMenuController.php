
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\FoodItem;
use App\Models\WeeklyMenu;
use App\Models\WeeklyMenuItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class WeeklyMenuController extends Controller
{
    public function index(): View
    {
        $weeklyMenus = WeeklyMenu::with([
            'weeklyMenuItems.foodItem.category',
        ])->orderBy('day_of_week')->get();

        $foodItems = FoodItem::with('category')
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($item) => $item->category?->name ?? 'Uncategorized');

        return view('admin.weekly-menus.index', compact(
            'weeklyMenus',
            'foodItems'
        ));
    }

    public function saveMenu(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:1,6'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $weeklyMenu = WeeklyMenu::updateOrCreate(
            ['day_of_week' => $validated['day_of_week']],
            [
                'title' => $validated['title'] ?? null,
                'is_active' => true,
            ]
        );

        if (today()->dayOfWeekIso === (int) $weeklyMenu->day_of_week) {
            DailyMenu::whereDate('menu_date', today()->toDateString())
                ->where('weekly_menu_id', $weeklyMenu->id)
                ->update([
                    'title' => $weeklyMenu->title,
                ]);
        }

        return back()->with('success', 'Weekly menu saved successfully.');
    }

    public function addItem(
        Request $request,
        WeeklyMenu $weeklyMenu
    ): RedirectResponse {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.food_item_id' => [
                'required',
                'integer',
                'exists:food_items,id',
            ],
            'items.*.selected' => ['nullable', 'in:1'],
            'items.*.price' => ['nullable', 'numeric', 'min:0'],
        ]);

        $selectedCount = 0;

        foreach ($validated['items'] as $itemData) {
            if (empty($itemData['selected'])) {
                continue;
            }

            if (!isset($itemData['price']) || $itemData['price'] === '') {
                return back()->withErrors([
                    'items' => 'Please enter a price for every selected meal.',
                ])->withInput();
            }

            $weeklyItem = WeeklyMenuItem::updateOrCreate(
                [
                    'weekly_menu_id' => $weeklyMenu->id,
                    'food_item_id' => $itemData['food_item_id'],
                ],
                [
                    'price' => $itemData['price'],
                    'is_available' => true,
                ]
            );

            $this->syncTodayItem(
                $weeklyMenu->id,
                $weeklyItem->food_item_id,
                $weeklyItem->price,
                true
            );

            $selectedCount++;
        }

        if ($selectedCount === 0) {
            return back()->withErrors([
                'items' => 'Please select at least one meal.',
            ]);
        }

        return back()->with('success', 'Selected meals saved successfully.');
    }

    public function removeItem(
        WeeklyMenu $weeklyMenu,
        WeeklyMenuItem $item
    ): RedirectResponse {
        if ($item->weekly_menu_id !== $weeklyMenu->id) {
            abort(404);
        }

        DB::transaction(function () use ($weeklyMenu, $item) {
            $today = today();

            // Hide today's daily record without deleting its ratings.
            if ($today->dayOfWeekIso === (int) $weeklyMenu->day_of_week) {
                DailyMenu::whereDate('menu_date', $today->toDateString())
                    ->where('weekly_menu_id', $weeklyMenu->id)
                    ->first()
                    ?->dailyMenuItems()
                    ->where('food_item_id', $item->food_item_id)
                    ->update([
                        'is_visible' => false,
                    ]);
            }

            // Remove the meal from this weekday's recurring menu.
            $item->delete();
        });

        return back()->with('success', 'Meal removed from the weekly menu.');
    }

    private function syncTodayItem(
        int $weeklyMenuId,
        int $foodItemId,
        mixed $price,
        bool $isAvailable,
        bool $forceAvailability = false
    ): void {
        $today = today();

        if ($today->dayOfWeekIso === 7) {
            return;
        }

        $dailyMenu = DailyMenu::whereDate(
            'menu_date',
            $today->toDateString()
        )
            ->where('weekly_menu_id', $weeklyMenuId)
            ->first();

        if (!$dailyMenu) {
            return;
        }

        $dailyItem = DailyMenuItem::firstOrNew([
            'daily_menu_id' => $dailyMenu->id,
            'food_item_id' => $foodItemId,
        ]);

        $dailyItem->price = $price;
        $dailyItem->is_visible = true;

        if ($forceAvailability) {
            $dailyItem->is_available = $isAvailable;
            $dailyItem->availability_override = null;
        } elseif (!$dailyItem->exists || $dailyItem->availability_override === null) {
            $dailyItem->is_available = $isAvailable;
        }

        $dailyItem->save();
    }
}
