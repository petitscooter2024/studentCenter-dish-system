<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\FoodItem;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function dashboard(): View
    {
        $todayMenu = DailyMenu::with([
            'dailyMenuItems.foodItem.category',
            'dailyMenuItems.ratings',
        ])
            ->whereDate('menu_date', today())
            ->first();

        $totalDishes = FoodItem::count();

        return view('staff.dashboard', compact(
            'todayMenu',
            'totalDishes'
        ));
    }

    public function storeMenu(
        Request $request,
        MenuService $menuService
    ): RedirectResponse {
        $validated = $request->validate([
            'menu_date' => ['required', 'date'],
            'title' => ['nullable', 'string', 'max:255'],
        ]);

        $menuService->createMenu($validated);

        return back()->with(
            'success',
            'Menu saved successfully.'
        );
    }
}
