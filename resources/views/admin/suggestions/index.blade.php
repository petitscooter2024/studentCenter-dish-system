@extends('layouts.app')

@section('title', 'Menu Suggestions | Admin')

@section('content')
<section class="container">
    <div class="section-heading">
        <h1>Staff Menu Suggestions</h1>
        <p>Review staff suggestions and publish changes to the official weekly menu.</p>
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

    @if($suggestions->count() > 0)
        <div style="display:grid; gap:22px;">
            @foreach($suggestions as $suggestion)
                <div class="form-card">
                    @php
                        $dayName = [
                            1 => 'Monday',
                            2 => 'Tuesday',
                            3 => 'Wednesday',
                            4 => 'Thursday',
                            5 => 'Friday',
                            6 => 'Saturday',
                        ][$suggestion->weeklyMenu?->day_of_week] ?? 'Unknown day';

                        $actionLabels = [
                            'add' => 'Add Meal',
                            'remove' => 'Remove a Meal',
                            'replace' => 'Replace a Meal',
                            'remove_category' => 'Remove an Entire Category',
                        ];

                        $actionLabel = $actionLabels[$suggestion->action]
                            ?? ucfirst(str_replace('_', ' ', $suggestion->action));
                    @endphp

                    <div style="display: flex; justify-content: space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <h3 style="color: #174b3a; margin-bottom:5px;">
                                {{ $actionLabel }}
                            </h3>
                            <p style="color: #758078; margin:0;">
                                {{ $dayName }} Menu
                            </p>
                        </div>

                        @if($suggestion->status === 'approved')
                            <span class="availability available">Published</span>
                        @elseif($suggestion->status === 'rejected')
                            <span class="availability unavailable">Rejected</span>
                        @else
                            <span class="badge">Pending Publication</span>
                        @endif
                    </div>

                    <hr style="margin: 20px 0; border:0; border-top: 1px solid #e6e9e4;">

                    <p>
                        <strong>Submitted by:</strong>
                        {{ $suggestion->submitter->name ?? 'Unknown staff member' }}
                    </p>

                    <p>
                        <strong>Action:</strong>
                        {{ $actionLabel }}
                    </p>

                    @if($suggestion->action === 'remove_category')
                        <p>
                            <strong>Category to remove:</strong>
                            {{ $suggestion->category?->name ?? 'Category unavailable' }}
                        </p>
                        <p>
                            <strong>Effect:</strong>
                            All meals belonging to this category will be removed from the selected weekday menu only.
                        </p>
                    @else
                        @if($suggestion->weeklyMenuItem)
                            <p>
                                <strong>Existing meal:</strong>
                                {{ $suggestion->weeklyMenuItem->foodItem?->name ?? 'Meal unavailable' }}
                            </p>
                        @endif

                        @if($suggestion->category)
                            <p>
                                <strong>Selected category:</strong>
                                {{ $suggestion->category->name }}
                            </p>
                        @endif

                        @if($suggestion->foodItem)
                            <p>
                                <strong>Suggested meal:</strong>
                                {{ $suggestion->foodItem->name }}
                            </p>
                        @endif
                    @endif

                    <div style="background: #faf9f5; padding: 15px; border-radius:10px; margin: 15px 0;">
                        <strong>Reason for suggestion</strong>
                        <p style="margin:8px 0 0; color: #59665e;">
                            {{ $suggestion->reason ?: 'No reason provided.' }}
                        </p>
                    </div>

                    @if($suggestion->status === 'pending')
                        <div style="display: flex; flex-wrap:wrap; gap:12px; margin-top: 20px;">
                            <form
                                action="{{ route('admin.suggestions.approve', $suggestion->id) }}"
                                method="POST"
                                style="flex:1; min-width: 250px;"
                            >
                                @csrf

                                @if(in_array($suggestion->action, ['add', 'replace']))
                                    <div class="form-group">
                                        <label>Set Meal Price (KSh)</label>
                                        <input
                                            type="number"
                                            name="price"
                                            min="0"
                                            step="0.01"
                                            placeholder="Enter meal price"
                                            required
                                        >
                                    </div>
                                @endif

                                <button
                                    type="submit"
                                    class="btn"
                                    onclick="return confirm('Publish this suggested change to the official weekly menu?')"
                                >
                                    Publish Menu
                                </button>
                            </form>

                            <form
                                action="{{ route('admin.suggestions.reject', $suggestion->id) }}"
                                method="POST"
                                style="align-self:flex-end;"
                                onsubmit="return confirm('Are you sure you want to reject this suggestion?')"
                            >
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Reject Suggestion
                                </button>
                            </form>
                        </div>
                    @else
                        <div style="margin-top:15px; color: #758078; font-size:14px;">
                            {{ $suggestion->status === 'approved' ? 'Published by:' : 'Reviewed by:' }}
                            {{ $suggestion->reviewer->name ?? 'Unknown' }}
                            @if($suggestion->reviewed_at)
                                on {{ $suggestion->reviewed_at->format('d M Y, H:i') }}
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <h3>No Suggestions Yet</h3>
            <p>Staff suggestions will appear here when submitted.</p>
        </div>
    @endif
</section>
@endsection