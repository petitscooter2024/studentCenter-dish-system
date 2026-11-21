<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\FoodItem;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DailyMenuController extends Controller
{
    public function index(): View
    {
        $menus = DailyMenu::with('dailyMenuItems.foodItem.category')
            ->orderByDesc('menu_date')
            ->get();

        return view('admin.daily-menus.index', compact('menus'));
    }

    public function store(
        Request $request,
        MenuService $menuService
    ): RedirectResponse {
        $validated = $request->validate([
            'menu_date' => ['required', 'date', 'unique:daily_menus,menu_date'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $menuService->createMenu($validated);

        return back()->with('success', 'Daily menu created successfully.');
    }

    public function addItem(
        Request $request,
        DailyMenu $dailyMenu,
        MenuService $menuService
    ): RedirectResponse {
        $validated = $request->validate([
            'food_item_id' => ['required', 'exists:food_items,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_available' => ['nullable', 'boolean'],
        ]);

        $validated['is_available'] = $request->boolean(
            'is_available',
            true
        );

        $menuService->addDish($dailyMenu, $validated);

        return back()->with('success', 'Dish added to the daily menu.');
    }

    public function updateItem(
        Request $request,
        DailyMenuItem $dailyMenuItem
    ): RedirectResponse {
        $validated = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'is_available' => ['required', 'boolean'],
        ]);

        $dailyMenuItem->update($validated);

        return back()->with('success', 'Menu item updated successfully.');
    }

    public function removeItem(
        DailyMenuItem $dailyMenuItem,
        MenuService $menuService
    ): RedirectResponse {
        $menuService->removeDish($dailyMenuItem);

        return back()->with('success', 'Dish removed from the daily menu.');
    }
}
