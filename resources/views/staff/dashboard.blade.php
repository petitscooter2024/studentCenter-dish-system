
@extends('layouts.app')

@section('title', 'Staff Dashboard | Dish Check')

@section('content')

<section class="container">

    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px; margin-bottom:35px;">

        <div>
            <p style="color:#758078; font-size:13px; font-weight:700; letter-spacing:1px; text-transform:uppercase;">
                Staff Portal
            </p>

            <h1 style="font-family:'Playfair Display',serif; color:#174b3a; font-size:34px;">
                Staff Dashboard
            </h1>

            <p style="color:#758078;">
                Manage today's meal availability and suggest weekly menu changes.
            </p>
        </div>

        <div style="background:#e8f1eb; padding:12px 20px; border-radius:10px; color:#174b3a; font-weight:600;">
            Staff Account
        </div>

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

    <div class="dashboard-grid">

        <div class="dashboard-card" style="border-left:5px solid #174b3a;">
            <p style="color:#758078; font-size:14px;">Total Food Items</p>
            <p class="dashboard-number">{{ $totalDishes }}</p>
            <p style="color:#758078; font-size:13px;">Registered food items</p>
        </div>

    </div>

    <div class="section-heading">
        <h2>Menu Suggestions</h2>
        <p>Suggest permanent changes to the weekly menu for Admin approval.</p>
    </div>

    <div class="feature-grid" style="margin-bottom:40px;">

        <div class="feature-card">
            <div style="font-size:30px; margin-bottom:12px;">💡</div>
            <h3>Submit a Suggestion</h3>
            <p style="color:#758078;">
                Suggest adding, removing or replacing a meal in the weekly menu.
            </p>
            <a href="{{ route('staff.suggestions.index') }}" class="btn">
                Submit Suggestion
            </a>
        </div>

        <div class="feature-card">
            <div style="font-size:30px; margin-bottom:12px;">📋</div>
            <h3>My Suggestions</h3>
            <p style="color:#758078;">
                Check the status of your submitted suggestions.
            </p>
            <a href="{{ route('staff.suggestions.index') }}" class="btn">
                View My Suggestions
            </a>
        </div>

    </div>

    <div class="section-heading">
        <h2>{{ $dayName }} Menu</h2>
        <p>Manage the availability of meals currently served today.</p>
    </div>

    @if($dayName === 'Sunday')

        <div class="empty-state">
            <h3>Cafeteria Closed</h3>
            <p>The cafeteria does not operate on Sundays.</p>
        </div>

    @elseif($dailyMenu && $dailyMenu->dailyMenuItems->count() > 0)

        <h3 style="color:#174b3a; margin-bottom:20px;">
            {{ $dailyMenu->title ?? 'Daily Special Menu' }}
        </h3>

        <div class="food-grid">

            @foreach($dailyMenu->dailyMenuItems as $item)

                @if($item->foodItem)

                    <div class="food-card">

                        <img
                            src="https://placehold.co/600x400?text={{ urlencode($item->foodItem->name) }}"
                            alt="{{ $item->foodItem->name }}"
                            class="food-image"
                        >

                        <div class="food-card-content">

                            <h3>{{ $item->foodItem->name }}</h3>

                            <p>
                                {{ $item->foodItem->description ?? 'Enjoy this meal at the Student Center.' }}
                            </p>

                            <p class="food-category">
                                {{ $item->foodItem->category?->name ?? 'General' }}
                            </p>

                            <p class="food-price">
                                KSh {{ number_format($item->price, 2) }}
                            </p>

                            @if($item->is_available)

                                <span class="availability available">
                                    Available
                                </span>

                                <form
                                    action="{{ route('staff.daily-menu-items.availability', $item->id) }}"
                                    method="POST"
                                    style="margin-top:15px;"
                                >
                                    @csrf

                                    <input type="hidden" name="is_available" value="0">

                                    <button type="submit" class="btn btn-danger">
                                        Mark Unavailable
                                    </button>
                                </form>

                            @else

                                <span class="availability unavailable">
                                    Unavailable
                                </span>

                                <form
                                    action="{{ route('staff.daily-menu-items.availability', $item->id) }}"
                                    method="POST"
                                    style="margin-top:15px;"
                                >
                                    @csrf

                                    <input type="hidden" name="is_available" value="1">

                                    <button type="submit" class="btn">
                                        Mark Available
                                    </button>
                                </form>

                            @endif

                        </div>

                    </div>

                @endif

            @endforeach

        </div>

    @else

        <div class="empty-state">
            <h3>No Menu Configured</h3>
            <p>No meals have been configured for {{ strtolower($dayName) }} yet.</p>
        </div>

    @endif

</section>

@endsection
