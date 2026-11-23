<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DailyMenuItem;
use App\Models\FoodItem;
use App\Models\WeeklyMenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FoodItemController extends Controller
{
    public function index(): View
    {
        $foodItems = FoodItem::with('category')
            ->latest()
            ->get();

        $categories = Category::orderBy('name')->get();

        return view('admin.food-items.index', compact(
            'foodItems',
            'categories'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        FoodItem::create($validated);

        return back()->with('success', 'Food item added successfully.');
    }

    public function update(
        Request $request,
        FoodItem $food_item
    ): RedirectResponse {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $food_item->update($validated);

        return back()->with('success', 'Food item updated successfully.');
    }

    public function destroy(FoodItem $food_item): RedirectResponse
    {
        $usedInWeeklyMenu = WeeklyMenuItem::where(
            'food_item_id',
            $food_item->id
        )->exists();

        $usedInDailyMenu = DailyMenuItem::where(
            'food_item_id',
            $food_item->id
        )->exists();

        if ($usedInWeeklyMenu || $usedInDailyMenu) {
            return back()->withErrors([
                'food_item' => 'This food item is already used in a menu and cannot be deleted. You can edit its details instead.',
            ]);
        }

        $food_item->delete();

        return back()->with('success', 'Food item deleted successfully.');
    }
}
