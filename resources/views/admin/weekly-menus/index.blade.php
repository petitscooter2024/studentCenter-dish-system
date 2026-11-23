@extends('layouts.app')

@section('title', 'Weekly Menu Management | Dish Check')

@section('content')
<section class="container">
    <div class="section-heading">
        <h1>Weekly Menu Management</h1>
        <p>Manage the cafeteria menu for Monday to Saturday.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];
    @endphp

    <div style="display:grid; gap: 25px;">
        @foreach($days as $dayNumber => $dayName)
            @php
                $menu = $weeklyMenus->firstWhere('day_of_week', $dayNumber);
            @endphp

            <div class="form-card">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap:wrap; gap:10px;">
                    <h2 style="color: #174b3a; margin:0;">
                        {{ $dayName }}
                    </h2>

                    @if($menu)
                        <span class="availability available">
                            Menu Configured
                        </span>
                    @else
                        <span class="availability unavailable">
                            Not Configured
                        </span>
                    @endif
                </div>

                <form
                    action="{{ route('admin.weekly-menus.store') }}"
                    method="POST"
                    style="margin-top:20px;"
                >
                    @csrf
                    <input
                        type="hidden"
                        name="day_of_week"
                        value="{{ $dayNumber }}"
                    >

                    <div class="form-group">
                        <label>Menu Title</label>
                        <input
                            type="text"
                            name="title"
                            value="{{ $menu?->title }}"
                            placeholder="{{ $dayName }} Special Menu"
                        >
                    </div>

                    <button type="submit" class="btn">
                        {{ $menu ? 'Update Menu Title' : 'Create ' . $dayName . ' Menu' }}
                    </button>
                </form>

                @if($menu)
                    <hr style="margin:25px 0; border:0; border-top:1px solid #e6e9e4;">

                    <h3>Selected Meals</h3>

                    @if($menu->weeklyMenuItems->count() > 0)
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Food Item</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Availability</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menu->weeklyMenuItems as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->foodItem?->name ?? 'Meal unavailable' }}</strong>
                                            </td>
                                            <td>
                                                {{ $item->foodItem?->category?->name ?? 'Other' }}
                                            </td>
                                            <td>
                                                KSh {{ number_format($item->price, 2) }}
                                            </td>
                                            <td>
                                                {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                            </td>
                                            <td>
                                                <form
                                                    action="{{ route('admin.weekly-menus.items.destroy', [$menu->id, $item->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Remove this meal from the weekly menu?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        Remove
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state" style="padding: 20px;">
                            <p>No meals selected for {{ $dayName }} yet.</p>
                        </div>
                    @endif

                    <details style="background: #faf9f5; padding: 20px; border-radius: 12px; margin-top: 25px;">
                        <summary style="cursor:pointer; color: #174b3a; font-weight:700; font-size:17px;">
                            Select Meals for {{ $dayName }}
                        </summary>

                        <div style="margin-top: 20px;">
                            <p style="color: #758078;">
                                Select meals and enter their prices.
                            </p>

                            <form
                                action="{{ route('admin.weekly-menus.items.store', $menu->id) }}"
                                method="POST"
                            >
                                @csrf

                                @foreach($foodItems as $categoryName => $categoryItems)
                                    <div style="margin-top:22px;">
                                        <h4 style="color:#174b3a; margin-bottom:12px;">
                                            {{ $categoryName }}
                                        </h4>

                                        <div style="display:grid; gap:12px;">
                                            @foreach($categoryItems as $foodItem)
                                                @php
                                                    $existingItem = $menu->weeklyMenuItems
                                                        ->firstWhere('food_item_id', $foodItem->id);
                                                @endphp

                                                <div style="display: flex; align-items:center; justify-content: space-between; gap:15px; flex-wrap:wrap; background: white; border:1px solid #e6e9e4; padding:12px; border-radius:8px;">
                                                    <div style="display: flex; align-items:center; gap:10px; flex:1;">
                                                        <input
                                                            type="hidden"
                                                            name="items[{{ $foodItem->id }}][food_item_id]"
                                                            value="{{ $foodItem->id }}"
                                                        >
                                                        <input
                                                            type="checkbox"
                                                            name="items[{{ $foodItem->id }}][selected]"
                                                            value="1"
                                                            id="food-{{ $dayNumber }}-{{ $foodItem->id }}"
                                                            {{ $existingItem ? 'checked' : '' }}
                                                            style="width:18px; height:18px;"
                                                        >
                                                        <label
                                                            for="food-{{ $dayNumber }}-{{ $foodItem->id }}"
                                                            style="margin:0; font-weight: 600;"
                                                        >
                                                            {{ $foodItem->name }}
                                                        </label>
                                                    </div>

                                                    <div style="width:150px;">
                                                        <input
                                                            type="number"
                                                            name="items[{{ $foodItem->id }}][price]"
                                                            value="{{ $existingItem?->price }}"
                                                            min="0"
                                                            step="0.01"
                                                            placeholder="Price (KSH)"
                                                            style="margin:0;"
                                                        >
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                <button
                                    type="submit"
                                    class="btn"
                                    style="margin-top: 25px;"
                                >
                                    Save {{ $dayName }} Menu
                                </button>
                            </form>
                        </div>
                    </details>
                @endif
            </div>
        @endforeach
    </div>
</section>
@endsection