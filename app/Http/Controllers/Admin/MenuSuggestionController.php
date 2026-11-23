<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\MenuSuggestion;
use App\Models\WeeklyMenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MenuSuggestionController extends Controller
{
    public function index(): View
    {
        $suggestions = MenuSuggestion::with([
            'weeklyMenu',
            'weeklyMenuItem.foodItem.category',
            'foodItem.category',
            'category',
            'submitter',
            'reviewer',
        ])
            ->latest()
            ->get();

        return view('admin.suggestions.index', compact('suggestions'));
    }

    public function approve(
        Request $request,
        MenuSuggestion $suggestion
    ): RedirectResponse {
        if ($suggestion->status !== 'pending') {
            return back()->withErrors([
                'suggestion' => 'This suggestion has already been reviewed.',
            ]);
        }

        $action = $suggestion->action;

        if (!in_array($action, ['add', 'replace', 'remove', 'remove_category'])) {
            return back()->withErrors([
                'suggestion' => 'This suggestion type is not supported.',
            ]);
        }

        $priceRules = in_array($action, ['add', 'replace'])
            ? ['required', 'numeric', 'min:0', 'max:99999999.99']
            : ['nullable'];

        $validated = $request->validate([
            'price' => $priceRules,
        ]);

        DB::transaction(function () use ($suggestion, $validated, $action) {
            $menu = $suggestion->weeklyMenu;

            if (!$menu || !$menu->is_active) {
                abort(422, 'The selected weekday menu is unavailable.');
            }

            if (in_array($action, ['remove', 'replace'])) {
                $existingItem = WeeklyMenuItem::where(
                    'weekly_menu_id',
                    $menu->id
                )
                    ->whereKey($suggestion->weekly_menu_item_id)
                    ->lockForUpdate()
                    ->first();

                if (!$existingItem) {
                    abort(422, 'The existing meal could not be found.');
                }
            }

            if (in_array($action, ['add', 'replace'])) {
                if (!$suggestion->food_item_id) {
                    abort(422, 'The suggested food item could not be found.');
                }

                $foodItem = \App\Models\FoodItem::find(
                    $suggestion->food_item_id
                );

                if (!$foodItem) {
                    abort(422, 'The suggested food item no longer exists.');
                }

                if (
                    $suggestion->category_id &&
                    (int) $foodItem->category_id !== (int) $suggestion->category_id
                ) {
                    abort(422, 'The suggested food item does not belong to the selected category.');
                }

                $alreadyExists = WeeklyMenuItem::where(
                    'weekly_menu_id',
                    $menu->id
                )
                    ->where('food_item_id', $suggestion->food_item_id)
                    ->exists();

                if ($alreadyExists) {
                    abort(422, 'This food item is already listed for this weekday.');
                }
            }

            if ($action === 'remove_category') {
                if (!$suggestion->category_id) {
                    abort(422, 'The category to remove could not be found.');
                }

                $items = WeeklyMenuItem::where(
                    'weekly_menu_id',
                    $menu->id
                )
                    ->whereHas('foodItem', function ($query) use ($suggestion) {
                        $query->where('category_id', $suggestion->category_id);
                    })
                    ->lockForUpdate()
                    ->get();

                if ($items->isEmpty()) {
                    abort(422, 'This category has no meals in the selected weekday menu.');
                }

                foreach ($items as $item) {
                    $item->update([
                        'is_available' => false,
                    ]);

                    $this->syncTodayItem(
                        $menu->id,
                        $item->food_item_id,
                        $item->price,
                        false
                    );
                }
            }

            if (in_array($action, ['remove', 'replace'])) {
                $existingItem->update([
                    'is_available' => false,
                ]);

                $this->syncTodayItem(
                    $menu->id,
                    $existingItem->food_item_id,
                    $existingItem->price,
                    false
                );
            }

            if (in_array($action, ['add', 'replace'])) {
                $newItem = WeeklyMenuItem::create([
                    'weekly_menu_id' => $menu->id,
                    'food_item_id' => $suggestion->food_item_id,
                    'price' => $validated['price'],
                    'is_available' => true,
                ]);

                $this->syncTodayItem(
                    $menu->id,
                    $newItem->food_item_id,
                    $newItem->price,
                    true
                );
            }

            $suggestion->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'The suggested change has been published to the weekly menu.'
        );
    }

    public function reject(MenuSuggestion $suggestion): RedirectResponse
    {
        if ($suggestion->status !== 'pending') {
            return back()->withErrors([
                'suggestion' => 'This suggestion has already been reviewed.',
            ]);
        }

        $suggestion->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with(
            'success',
            'The menu suggestion has been rejected.'
        );
    }

    private function syncTodayItem(
        int $weeklyMenuId,
        int $foodItemId,
        mixed $price,
        bool $isAvailable
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
        $dailyItem->is_available = $isAvailable;
        $dailyItem->availability_override = null;
        $dailyItem->save();
    }
}
