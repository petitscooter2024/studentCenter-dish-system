<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FoodItem;
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

        $categories = Category::all();

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
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('food-items', 'public');
        }

        FoodItem::create($validated);

        return back()->with('success', 'Food item added successfully.');
    }

    public function update(Request $request, FoodItem $foodItem): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('food-items', 'public');
        }

        $foodItem->update($validated);

        return back()->with('success', 'Food item updated successfully.');
    }

    public function destroy(FoodItem $foodItem): RedirectResponse
    {
        $foodItem->delete();

        return back()->with('success', 'Food item deleted successfully.');
    }
}