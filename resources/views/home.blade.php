
@extends('layouts.app')

@section('title', 'Home | Student Center Dish Check')

@section('content')

<style>
    /* HOMEPAGE HERO */
    .dish-hero {
        position: relative;
        overflow: hidden;
        background:
            linear-gradient(
                90deg,
                rgba(9, 39, 28, 0.82),
                rgba(9, 39, 28, 0.35)
            ),
            url('/images/dish-check-hero.jpg') center center / cover no-repeat;
        padding: 90px 0;
        min-height: 590px;
        display: flex;
        align-items: center;
    }

    .dish-hero-inner {
        width: min(1380px, 90%);
        margin: auto;
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 55px;
        align-items: center;
    }

    .dish-hero-label {
        color: #e0eee5;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 13px;
        margin-bottom: 15px;
    }

    .dish-hero h1 {
        color: #fff;
        font-family: 'Playfair Display', serif;
        font-size: clamp(42px, 5vw, 68px);
        line-height: 1.08;
        margin: 15px 0 22px;
        letter-spacing: -1px;
    }

    .dish-hero-copy {
        color: rgba(255,255,255,.94);
        font-size: 17px;
        line-height: 1.8;
        max-width: 620px;
        margin-bottom: 28px;
    }

    .dish-hero .btn {
        display: inline-block;
        background: #176047;
        color: white;
        border: 1px solid rgba(255,255,255,.65);
        padding: 15px 27px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 700;
        transition: .2s ease;
    }

    .dish-hero .btn:hover {
        background: #10382c;
        transform: translateY(-2px);
    }

    /* WELCOME CARD */
    .dish-welcome {
        background: rgba(248, 250, 247, .94);
        padding: 40px;
        border-radius: 26px;
        border: 1px solid rgba(255,255,255,.6);
        box-shadow: 0 15px 45px rgba(0,0,0,.15);
    }

    .dish-welcome-label {
        color: #758078;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .dish-welcome h2 {
        color: #174b3a;
        font-family: 'Playfair Display', serif;
        font-size: 30px;
        line-height: 1.3;
        margin-bottom: 15px;
    }

    .dish-welcome-description {
        color: #59665e;
        line-height: 1.9;
        font-size: 16px;
    }

    .dish-welcome-footer {
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #d1dfd5;
        color: #174b3a;
        font-weight: 700;
        font-size: 14px;
    }

    /* MENU PREVIEW */
    .home-menu-section {
        padding-top: 35px;
        padding-bottom: 65px;
    }

    .home-menu-heading {
        margin-bottom: 25px;
    }

    .home-menu-heading .eyebrow {
        color: #758078;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .home-menu-heading h2 {
        color: #174b3a;
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        margin-bottom: 8px;
    }

    .home-menu-heading p {
        color: #758078;
    }

    .home-food-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
        margin-top: 25px;
    }

    .home-food-card {
        background: #fff;
        border: 1px solid #e6eae5;
        border-radius: 17px;
        overflow: hidden;
        box-shadow: 0 7px 22px rgba(23,75,58,.06);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .home-food-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 13px 28px rgba(23,75,58,.12);
    }

    .home-food-image {
        width: 100%;
        height: 175px;
        object-fit: cover;
        display: block;
        background: #e8f1eb;
    }

    .home-food-content {
        padding: 17px;
    }

    .home-food-content h3 {
        color: #174b3a;
        font-size: 18px;
        margin-bottom: 8px;
    }

    .home-food-description {
        color: #68756d;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 9px;
    }

    .home-food-category {
        color: #879188;
        font-size: 12px;
        margin-bottom: 12px;
    }

    .home-food-price {
        color: #174b3a;
        font-size: 17px;
        font-weight: 700;
        margin: 12px 0;
    }

    .home-availability {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }

    .home-availability.available {
        background: #e1f3e6;
        color: #187442;
    }

    .home-availability.unavailable {
        background: #fce7e5;
        color: #bd3434;
    }

    .home-menu-action {
        text-align: center;
        margin-top: 38px;
    }

    /* RESPONSIVE */
    @media (max-width: 950px) {
        .dish-hero {
            padding: 65px 0;
        }

        .dish-hero-inner {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .home-food-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 520px) {
        .dish-hero {
            min-height: auto;
            padding: 55px 0;
        }

        .dish-hero-inner {
            width: 90%;
        }

        .dish-welcome {
            padding: 25px;
        }

        .home-food-grid {
            grid-template-columns: 1fr;
        }

        .home-food-image {
            height: 200px;
        }
    }
</style>

{{-- HERO SECTION --}}
<section class="dish-hero">
    <div class="dish-hero-inner">

        <div>
            <p class="dish-hero-label">
                Student Center Dish Check
            </p>

            <h1>
                Good food,<br>
                better choices.
            </h1>

            <p class="dish-hero-copy">
                Discover today's cafeteria meals, check their prices
                and share your feedback. Your opinion helps us make
                every meal better.
            </p>

            <a href="{{ route('menu.index') }}" class="btn">
                Explore Today's Menu
            </a>
        </div>

        <div class="dish-welcome">
            <p class="dish-welcome-label">
                Welcome
            </p>

            <h2>Welcome to Dish Check</h2>

            <p class="dish-welcome-description">
                Find out what's being served at the Student Center
                and help us improve the cafeteria experience through
                your feedback.
            </p>

            <div class="dish-welcome-footer">
                Fresh meals. Fair prices. Your feedback.
            </div>
        </div>

    </div>
</section>

{{-- TODAY'S MENU --}}
<section class="container home-menu-section">

    <div class="home-menu-heading">
        <p class="eyebrow">What's cooking today?</p>

        <h2>Today's Menu</h2>

        <p>Here are the meals available for {{ $dayName }}.</p>
    </div>

    @if($dayName === 'Sunday')

        <div class="empty-state">
            <h3>Cafeteria Closed</h3>
            <p>The cafeteria does not operate on Sundays. See you on Monday!</p>
        </div>

    @elseif($dailyMenu && $dailyMenu->dailyMenuItems->count() > 0)

        <h3 style="color:#174b3a;font-family:'Playfair Display',serif;font-size:23px;margin-bottom:20px;">
            {{ $dailyMenu->title ?? "Today's Special Menu" }}
        </h3>

        <div class="home-food-grid">

            @foreach($dailyMenu->dailyMenuItems as $item)

                @if($item->foodItem)

                    @php
    $weeklyItem = $weeklyMenu?->weeklyMenuItems
        ->firstWhere('food_item_id', $item->food_item_id);

    $isAvailable = $item->is_available
        && ($weeklyItem?->is_available ?? false);
@endphp

                    <div class="home-food-card">

                        <img
                            src="{{ $item->foodItem->image ?: 'https://placehold.co/600x400/e8f1eb/174b3a?text=Food+Image' }}"
                            alt="{{ $item->foodItem->name }}"
                            class="home-food-image"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://placehold.co/600x400/e8f1eb/174b3a?text=Image+Unavailable';"
                        >

                        <div class="home-food-content">

                            <h3>{{ $item->foodItem->name }}</h3>

                            <p class="home-food-description">
                                {{ $item->foodItem->description ?? 'Enjoy this meal at the Student Center.' }}
                            </p>

                            <p class="home-food-category">
                                {{ $item->foodItem->category?->name ?? 'General' }}
                            </p>

                            <p class="home-food-price">
                                KSh {{ number_format($item->price, 2) }}
                            </p>

                            @if($isAvailable)
                                <span class="home-availability available">
                                    Available
                                </span>
                            @else
                                <span class="home-availability unavailable">
                                    Not Available
                                </span>
                            @endif

                        </div>
                    </div>

                @endif

            @endforeach

        </div>

        <div class="home-menu-action">
            <a href="{{ route('menu.index') }}" class="btn">
                View Full Menu
            </a>
        </div>

    @else

        <div class="empty-state">
            <h3>No Menu Available</h3>
            <p>No meals have been configured for {{ strtolower($dayName) }} yet.</p>
        </div>

    @endif

</section>

@endsection
