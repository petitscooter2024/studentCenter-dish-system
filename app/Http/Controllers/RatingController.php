<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class RatingController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'daily_menu_item_id' => ['required', 'exists:daily_menu_items,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $token = $request->cookie('dish_check_token');

        if (!$token) {
            $token = Str::random(64);
        }

        $tokenHash = hash('sha256', $token);

        $alreadyRated = Rating::where(
            'daily_menu_item_id',
            $validated['daily_menu_item_id']
        )
        ->where('anonymous_token_hash', $tokenHash)
        ->exists();

        if ($alreadyRated) {
            return back()->with('error', 'You have already rated this dish today.');
        }

        Rating::create([
            'daily_menu_item_id' => $validated['daily_menu_item_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'anonymous_token_hash' => $tokenHash,
        ]);

        return back()
            ->with('success', 'Thank you for rating this dish!')
            ->cookie('dish_check_token', $token, 60 * 24 * 365);
    }
}