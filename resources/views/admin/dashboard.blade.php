
@extends('layouts.app')

@section('content')
<div class="container">

    <div class="page-header">
        <h1>Admin Dashboard</h1>
        <p>Manage the cafeteria menu and monitor student feedback.</p>
    </div>

    <div class="dashboard-stats">
        <div class="form-card">
            <h3>Total Weekly Menus</h3>
            <h2>{{ $totalMenus }}</h2>
        </div>

        <div class="form-card">
            <h3>Total Ratings</h3>
            <h2>{{ $totalRatings }}</h2>
        </div>

        <div class="form-card">
            <h3>Staff Members</h3>
            <h2>{{ $totalStaff }}</h2>
        </div>
    </div>

    <div class="section-header">
        <h2>Management</h2>
    </div>

    <div class="dashboard-grid">

        <a href="{{ route('admin.categories.index') }}" class="form-card">
            <h3>Categories</h3>
            <p>Manage food categories.</p>
        </a>

        <a href="{{ route('admin.food-items.index') }}" class="form-card">
            <h3>Food Items</h3>
            <p>Add and manage cafeteria meals.</p>
        </a>

        <a href="{{ route('admin.weekly-menus.index') }}" class="form-card">
            <h3>Weekly Menus</h3>
            <p>Set up the menus for Monday to Saturday.</p>
        </a>

        <a href="{{ route('admin.suggestions.index') }}" class="form-card">
            <h3>Menu Suggestions</h3>
            <p>Review and publish staff suggestions.</p>
        </a>

        <a href="{{ route('admin.staff.index') }}" class="form-card">
            <h3>Staff Management</h3>
            <p>Manage staff accounts.</p>
        </a>

        <a href="{{ route('admin.ratings.index') }}" class="form-card">
            <h3>Student Ratings</h3>
            <p>View all student ratings and comments.</p>
        </a>

    </div>

    <div class="section-header">
        <h2>Recent Ratings</h2>
    </div>

    <div class="form-card">
        @if($recentRatings->isEmpty())
            <p class="empty-state">No ratings have been submitted yet.</p>
        @else
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Food Item</th>
                            <th>Rating</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($recentRatings as $rating)
                            @php
                                $foodItem = $rating->weeklyMenuItem?->foodItem
                                    ?? $rating->dailyMenuItem?->foodItem;
                            @endphp

                            <tr>
                                <td>{{ $foodItem?->name ?? 'Meal unavailable' }}</td>
                                <td>{{ $rating->rating }}/5</td>
                                <td>{{ $rating->comment ?: 'No comment' }}</td>
                                <td>{{ $rating->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.ratings.index') }}" class="btn">
                View All Ratings
            </a>
        @endif
    </div>

</div>
@endsection
