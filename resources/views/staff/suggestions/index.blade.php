@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>Menu Suggestions</h1>
        <p>Suggest changes to the weekly cafeteria menu.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="form-card">
        <h2>Submit Suggestion</h2>
        <form method="POST" action="{{ route('staff.suggestions.store') }}">
            @csrf

            <label for="weekly_menu_id">Select Day</label>
            <select name="weekly_menu_id" id="weekly_menu_id" required>
                <option value="">Choose day</option>
                @foreach($weeklyMenus as $menu)
                    <option
                        value="{{ $menu->id }}"
                        {{ old('weekly_menu_id') == $menu->id ? 'selected' : '' }}
                    >
                        {{ [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'][$menu->day_of_week] ?? 'Unknown day' }}
                    </option>
                @endforeach
            </select>

            <label for="action">Suggestion Type</label>
            <select name="action" id="action" required>
                <option value="">Choose an action</option>
                <option value="add" {{ old('action') === 'add' ? 'selected' : '' }}>Add a meal</option>
                <option value="remove" {{ old('action') === 'remove' ? 'selected' : '' }}>Remove a meal</option>
                <option value="replace" {{ old('action') === 'replace' ? 'selected' : '' }}>Replace a meal</option>
                <option value="remove_category" {{ old('action') === 'remove_category' ? 'selected' : '' }}>Remove entire category</option>
            </select>

            <div id="existing-meal-section">
                <label for="weekly_menu_item_id">Existing Meal to Change</label>
                <select name="weekly_menu_item_id" id="weekly_menu_item_id">
                    <option value="">Choose existing meal</option>
                    @foreach($weeklyMenus as $menu)
                        @foreach($menu->weeklyMenuItems as $item)
                            <option
                                value="{{ $item->id }}"
                                data-day="{{ $menu->id }}"
                                {{ old('weekly_menu_item_id') == $item->id ? 'selected' : '' }}
                            >
                                {{ $item->foodItem?->name ?? 'Meal unavailable' }}
                            </option>
                        @endforeach
                    @endforeach
                </select>
            </div>

            <div id="category-section">
                <label for="category_id">Select Category</label>
                <select name="category_id" id="category_id">
                    <option value="">Choose category</option>
                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            {{ old('category_id') == $category->id ? 'selected' : '' }}
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="food-item-section">
                <label for="food_item_id">Select Food Item</label>
                <select name="food_item_id" id="food_item_id">
                    <option value="">Choose a food item</option>
                    @foreach($foodItems as $food)
                        <option
                            value="{{ $food->id }}"
                            data-category="{{ $food->category_id }}"
                            {{ old('food_item_id') == $food->id ? 'selected' : '' }}
                        >
                            {{ $food->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label for="reason">Reason for Suggestion (Optional)</label>
            <textarea
                name="reason"
                id="reason"
                rows="4"
                maxlength="1000"
                placeholder="Explain why you are suggesting this change..."
            >{{ old('reason') }}</textarea>

            <button type="submit" class="btn">Submit Suggestion</button>
        </form>
    </div>

    <div class="form-card">
        <h2>My Suggestions</h2>

        @if($suggestions->isEmpty())
            <p class="empty-state">You have not submitted any suggestions yet.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Action</th>
                            <th>Category</th>
                            <th>Existing Meal</th>
                            <th>Suggested Meal</th>
                            <th>Reason</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suggestions as $suggestion)
                            <tr>
                                <td>
                                    {{ $suggestion->weeklyMenu
                                        ? ([1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'][$suggestion->weeklyMenu->day_of_week] ?? 'Unknown')
                                        : 'Unavailable' }}
                                </td>
                                <td>
                                    {{ [
                                        'add' => 'Add a meal',
                                        'remove' => 'Remove meal',
                                        'replace' => 'Replace a meal',
                                        'remove_category' => 'Remove category',
                                    ][$suggestion->action] ?? ucfirst($suggestion->action) }}
                                </td>
                                <td>{{ $suggestion->category?->name ?? '-' }}</td>
                                <td>{{ $suggestion->weeklyMenuItem?->foodItem?->name ?? '-' }}</td>
                                <td>{{ $suggestion->foodItem?->name ?? '-' }}</td>
                                <td>{{ $suggestion->reason ?: '-' }}</td>
                                <td>{{ $suggestion->status === 'approved' ? 'Published' : ucfirst($suggestion->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const daySelect = document.getElementById('weekly_menu_id');
    const actionSelect = document.getElementById('action');
    const mealSelect = document.getElementById('weekly_menu_item_id');
    const categorySelect = document.getElementById('category_id');
    const foodSelect = document.getElementById('food_item_id');

    const existingMealSection = document.getElementById('existing-meal-section');
    const categorySection = document.getElementById('category-section');
    const foodItemSection = document.getElementById('food-item-section');

    const oldMealId = @json(old('weekly_menu_item_id', ''));
    const oldFoodId = @json(old('food_item_id', ''));

    function updateMeals() {
        const selectedDay = daySelect.value;

        Array.from(mealSelect.options).forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }
            option.hidden = !selectedDay || option.dataset.day !== selectedDay;
        });

        if (mealSelect.selectedOptions[0]?.hidden) {
            mealSelect.value = '';
        }

        if (oldMealId) {
            const oldOption = Array.from(mealSelect.options).find(
                option => option.value === String(oldMealId)
            );
            if (oldOption && !oldOption.hidden) {
                mealSelect.value = oldMealId;
            }
        }
    }

    function updateFoodItems() {
        const selectedCategory = categorySelect.value;

        Array.from(foodSelect.options).forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }
            option.hidden = !selectedCategory || option.dataset.category !== selectedCategory;
        });

        if (foodSelect.selectedOptions[0]?.hidden) {
            foodSelect.value = '';
        }

        if (oldFoodId) {
            const oldOption = Array.from(foodSelect.options).find(
                option => option.value === String(oldFoodId)
            );
            if (oldOption && !oldOption.hidden) {
                foodSelect.value = oldFoodId;
            }
        }
    }

    function updateFields() {
        const action = actionSelect.value;
        const needsExistingMeal = ['remove', 'replace'].includes(action);
        const needsCategory = ['add', 'replace', 'remove_category'].includes(action);
        const needsFood = ['add', 'replace'].includes(action);

        existingMealSection.hidden = !needsExistingMeal;
        categorySection.hidden = !needsCategory;
        foodItemSection.hidden = !needsFood;

        mealSelect.required = needsExistingMeal;
        categorySelect.required = needsCategory;
        foodSelect.required = needsFood;

        if (!needsExistingMeal) {
            mealSelect.value = '';
        }
        if (!needsCategory) {
            categorySelect.value = '';
            foodSelect.value = '';
        }
        if (!needsFood) {
            foodSelect.value = '';
        }

        updateMeals();
        updateFoodItems();
    }

    daySelect.addEventListener('change', updateMeals);
    actionSelect.addEventListener('change', updateFields);
    categorySelect.addEventListener('change', function () {
        foodSelect.value = '';
        updateFoodItems();
    });

    updateMeals();
    updateFoodItems();
    updateFields();
});
</script>
@endsection