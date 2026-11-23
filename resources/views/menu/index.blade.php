
@extends('layouts.app')

@section('title', "Today's Menu | Dish Check")

@section('content')

<style>
    .daily-menu-page {
        padding-top: 35px;
        padding-bottom: 65px;
    }

    .daily-menu-header {
        margin-bottom: 35px;
    }

    .daily-menu-header .eyebrow {
        color: #758078;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .daily-menu-header h1 {
        color: #174b3a;
        font-family: 'Playfair Display', serif;
        font-size: clamp(34px, 4vw, 48px);
        margin-bottom: 8px;
    }

    .daily-menu-header p {
        color: #758078;
        font-size: 16px;
    }

    .daily-menu-title {
        margin-bottom: 25px;
    }

    .daily-menu-title h2 {
        color: #174b3a;
        font-family: 'Playfair Display', serif;
        font-size: 27px;
        margin-bottom: 5px;
    }

    .daily-menu-title p {
        color: #758078;
        font-size: 14px;
    }

    .daily-menu-search {
        margin: 25px 0 30px;
    }

    .daily-menu-search label {
        display: block;
        font-weight: 600;
        color: #263b30;
        margin-bottom: 9px;
    }

    .daily-menu-search input {
        width: 100%;
        padding: 14px 17px;
        border: 1px solid #dce3dd;
        border-radius: 11px;
        background: white;
        font-size: 15px;
    }

    .daily-menu-search input:focus {
        outline: none;
        border-color: #174b3a;
        box-shadow: 0 0 0 3px rgba(23,75,58,.08);
    }

    /* CATEGORY ACCORDION */
    .menu-category-section {
        margin-bottom: 15px;
        border: 1px solid #e3e9e3;
        border-radius: 15px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 5px 18px rgba(23,75,58,.045);
    }

    .menu-category-toggle {
        list-style: none;
        cursor: pointer;
        padding: 20px 23px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        background: #fff;
        transition: background .2s ease;
    }

    .menu-category-toggle::-webkit-details-marker {
        display: none;
    }

    .menu-category-toggle:hover {
        background: #f5f8f5;
    }

    .menu-category-name {
        color: #174b3a;
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        font-weight: 700;
    }

    .menu-category-count {
        color: #758078;
        background: #edf4ef;
        border-radius: 30px;
        padding: 5px 11px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .menu-category-arrow {
        color: #174b3a;
        font-size: 18px;
        transition: transform .2s ease;
    }

    .menu-category-section[open] .menu-category-arrow {
        transform: rotate(180deg);
    }

    .menu-category-content {
        padding: 5px 22px 23px;
        border-top: 1px solid #edf0ed;
    }

    /* FOOD CARDS */
    .daily-food-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        align-items: stretch;
        padding-top: 18px;
    }

    .daily-food-card {
        background: white;
        border: 1px solid #e5eae5;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(23,75,58,.055);
        display: flex;
        flex-direction: column;
        min-width: 0;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .daily-food-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(23,75,58,.10);
    }

    .daily-food-image {
        display: block;
        width: 100%;
        height: 165px;
        object-fit: cover;
        background: #e8f1eb;
    }

    .daily-food-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .daily-food-content h3 {
        color: #174b3a;
        font-size: 18px;
        line-height: 1.35;
        margin: 0 0 7px;
    }

    .daily-food-description {
        color: #59665e;
        font-size: 13px;
        line-height: 1.55;
        margin-bottom: 8px;
        min-height: 40px;
    }

    .daily-food-category {
        color: #879188;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .daily-food-price {
        color: #174b3a;
        font-size: 17px;
        font-weight: 700;
        margin: 5px 0 12px;
    }

    .daily-food-card .availability {
        align-self: flex-start;
        font-size: 12px;
        padding: 7px 12px;
        border-radius: 30px;
    }

    .daily-rating {
        border-top: 1px solid #e9eee9;
        margin-top: 16px;
        padding-top: 14px;
    }

    .daily-rating h4 {
        color: #174b3a;
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        margin-bottom: 12px;
    }

    .daily-rating .form-group {
        margin-bottom: 11px;
    }

    .daily-rating label {
        display: block;
        color: #34483b;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .daily-rating select,
    .daily-rating textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #dce3dd;
        border-radius: 8px;
        background: white;
        font-size: 13px;
    }

    .daily-rating textarea {
        min-height: 75px;
        resize: vertical;
    }

    .daily-rating .btn {
        width: 100%;
        padding: 11px;
        font-size: 13px;
        text-align: center;
    }

    .daily-unavailable {
        margin-top: 15px;
        padding-top: 13px;
        border-top: 1px solid #e9eee9;
    }

    @media (max-width: 1100px) {
        .daily-food-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 760px) {
        .daily-food-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px;
        }

        .daily-food-image {
            height: 145px;
        }

        .menu-category-toggle {
            padding: 17px;
        }

        .menu-category-content {
            padding: 5px 15px 18px;
        }
    }

    @media (max-width: 480px) {
        .daily-food-grid {
            grid-template-columns: 1fr;
        }

        .daily-food-image {
            height: 200px;
        }
    }
</style>

<section class="container daily-menu-page">

    <div class="daily-menu-header">
        <p class="eyebrow">Fresh from the cafeteria</p>
        <h1>Today's Menu</h1>
        <p>Explore today's meals, check their prices and share your feedback.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($dayName === 'Sunday')

        <div class="empty-state">
            <h3>Cafeteria Closed</h3>
            <p>The cafeteria does not operate on Sundays. See you on Monday!</p>
        </div>

    @elseif($dailyMenu && $dailyMenu->dailyMenuItems->count() > 0)

        <div class="daily-menu-title">
            <h2>{{ $dailyMenu->title ?? "Today's Special Menu" }}</h2>
            <p>{{ $dayName }}</p>
        </div>

        <div class="daily-menu-search">
            <label for="menu-search">Search for a meal</label>
            <input
                type="search"
                id="menu-search"
                placeholder="Search food, e.g. pilau..."
                autocomplete="off"
            >
        </div>

        <div id="menu-search-results">

            @foreach($itemsByCategory as $categoryName => $categoryItems)

                <details class="menu-category-section" data-category-section>

                    <summary class="menu-category-toggle">

                        <span class="menu-category-name">
                            {{ $categoryName }}
                        </span>

                        <span style="display:flex;align-items:center;gap:12px;">
                            <span class="menu-category-count">
                                {{ $categoryItems->count() }}
                                {{ $categoryItems->count() === 1 ? 'meal' : 'meals' }}
                            </span>

                            <span class="menu-category-arrow">
                                &#9662;
                            </span>
                        </span>

                    </summary>

                    <div class="menu-category-content">

                        <div class="daily-food-grid">

                            @foreach($categoryItems as $item)

                                @if($item->foodItem)

                                    @php
                                        $weeklyItem = $menu?->weeklyMenuItems
                                            ->firstWhere('food_item_id', $item->food_item_id);

                                        $isAvailable = $item->is_available
                                            && ($weeklyItem?->is_available ?? false);

                                        $searchText = strtolower(
                                            $item->foodItem->name . ' ' .
                                            ($item->foodItem->description ?? '')
                                        );
                                    @endphp

                                    <div
                                        class="daily-food-card menu-search-item"
                                        data-search="{{ $searchText }}"
                                    >

                                        <img
                                            src="{{ $item->foodItem->image ?: 'https://placehold.co/600x400/e8f1eb/174b3a?text=Food+Image' }}"
                                            alt="{{ $item->foodItem->name }}"
                                            class="daily-food-image"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='https://placehold.co/600x400/e8f1eb/174b3a?text=Image+Unavailable';"
                                        >

                                        <div class="daily-food-content">

                                            <h3>{{ $item->foodItem->name }}</h3>

                                            <p class="daily-food-description">
                                                {{ $item->foodItem->description ?? 'Enjoy this meal at the Student Center.' }}
                                            </p>

                                            <p class="daily-food-category">
                                                {{ $item->foodItem->category?->name ?? 'General' }}
                                            </p>

                                            <p class="daily-food-price">
                                                KSh {{ number_format($item->price, 2) }}
                                            </p>

                                            @if($isAvailable)

                                                <span class="availability available">
                                                    Available
                                                </span>

                                                @if($weeklyItem)

                                                    <div class="daily-rating">

                                                        <h4>Rate this meal</h4>

                                                        <form action="{{ route('ratings.store') }}" method="POST">
                                                            @csrf

                                                            <input
                                                                type="hidden"
                                                                name="weekly_menu_item_id"
                                                                value="{{ $weeklyItem->id }}"
                                                            >

                                                            <div class="form-group">
                                                                <label>Rating</label>

                                                                <select name="rating" required>
                                                                    <option value="">Choose a rating</option>
                                                                    <option value="5">5 - Excellent</option>
                                                                    <option value="4">4 - Very Good</option>
                                                                    <option value="3">3 - Good</option>
                                                                    <option value="2">2 - Fair</option>
                                                                    <option value="1">1 - Poor</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label>Comment (Optional)</label>

                                                                <textarea
                                                                    name="comment"
                                                                    rows="3"
                                                                    maxlength="1000"
                                                                    placeholder="Share your feedback..."
                                                                ></textarea>
                                                            </div>

                                                            <button type="submit" class="btn">
                                                                Submit Rating
                                                            </button>
                                                        </form>

                                                    </div>

                                                @endif

                                            @else

                                                <div class="daily-unavailable">
                                                    <span class="availability unavailable">
                                                        Not Available
                                                    </span>
                                                </div>

                                            @endif

                                        </div>
                                    </div>

                                @endif

                            @endforeach

                        </div>

                    </div>

                </details>

            @endforeach

        </div>

        <div id="no-menu-results" class="empty-state" hidden>
            <h3>No Matching Meals</h3>
            <p>Try searching with another food name or description.</p>
        </div>

    @else

        <div class="empty-state">
            <h3>No Menu Available</h3>
            <p>No meals have been configured for {{ strtolower($dayName) }} yet.</p>
        </div>

    @endif

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('menu-search');

    if (!searchInput) {
        return;
    }

    const foodCards = document.querySelectorAll('.menu-search-item');
    const categorySections = document.querySelectorAll('[data-category-section]');
    const noResults = document.getElementById('no-menu-results');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim().toLowerCase();
        let totalVisible = 0;

        foodCards.forEach(function (card) {
            const text = card.dataset.search || '';
            const matches = text.includes(searchTerm);

            card.hidden = !matches;

            if (matches) {
                totalVisible++;
            }
        });

        categorySections.forEach(function (section) {
            const visibleCards = section.querySelectorAll(
                '.menu-search-item:not([hidden])'
            );

            const hasMatches = visibleCards.length > 0;

            section.hidden = !hasMatches;

            if (searchTerm !== '') {
                section.open = hasMatches;
            } else {
                section.open = false;
            }
        });

        noResults.hidden = totalVisible > 0;
    });
});
</script>

@endsection
