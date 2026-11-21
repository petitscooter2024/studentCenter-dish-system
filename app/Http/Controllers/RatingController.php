<?php

namespace App\Http\Controllers;

use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RatingController extends Controller
{
    public function store(
        Request $request,
        RatingService $ratingService
    ): RedirectResponse {
        $validated = $request->validate([
            'daily_menu_item_id' => ['required', 'exists:daily_menu_items,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        $token = $request->cookie('dish_check_token');

        if (!$token) {
            $token = $ratingService->generateAnonymousToken();
        }

        $ratingService->submitRating(
            (int) $validated['daily_menu_item_id'],
            (int) $validated['rating'],
            $validated['comment'] ?? null,
            $token
        );

        return back()
            ->with('success', 'Thank you for rating this dish!')
            ->cookie('dish_check_token', $token, 60 * 24 * 365);
    }
}