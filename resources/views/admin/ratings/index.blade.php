
@extends('layouts.app')

@section('content')
<div class="container">

    <div class="page-header">
        <h1>Student Ratings</h1>
        <p>View all ratings and comments submitted by students.</p>
    </div>

    <div class="form-card">

        @if($ratings->isEmpty())
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
                        @foreach($ratings as $rating)
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

            {{ $ratings->links() }}
        @endif

    </div>

    <a href="{{ route('admin.dashboard') }}" class="btn">
        Back to Dashboard
    </a>

</div>
@endsection
