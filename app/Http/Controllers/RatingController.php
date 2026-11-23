<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\WeeklyMenuItem;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function store(
        Request $request,
        RatingService $ratingService
    ): RedirectResponse {
        $validated = $request->validate([
            'weekly_menu_item_id' => [
                'required',
                'integer',
                'exists:weekly_menu_items,id',
            ],
            'rating' => [
                'required',
                'integer',
                'between:1,5',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $item = WeeklyMenuItem::with('weeklyMenu')
            ->findOrFail($validated['weekly_menu_item_id']);

        if (
            !$item->weeklyMenu ||
            !$item->weeklyMenu->is_active ||
            !$item->is_available ||
            (int) $item->weeklyMenu->day_of_week !== today()->dayOfWeekIso
        ) {
            return back()->withErrors([
                'rating' => 'This meal is currently unavailable for rating.',
            ]);
        }

        $dailyMenu = DailyMenu::whereDate(
            'menu_date',
            today()->toDateString()
        )
            ->where('weekly_menu_id', $item->weekly_menu_id)
            ->first();

        if (!$dailyMenu) {
            return back()->withErrors([
                'rating' => "Today's menu is not available for rating.",
            ]);
        }

        $dailyItem = DailyMenuItem::where(
            'daily_menu_id',
            $dailyMenu->id
        )
            ->where('food_item_id', $item->food_item_id)
            ->first();

        if (!$dailyItem || !$dailyItem->is_available) {
            return back()->withErrors([
                'rating' => 'This meal is currently unavailable for rating.',
            ]);
        }

        $token = $request->cookie('anonymous_rating_token');

        if (!$token) {
            $token = $ratingService->generateAnonymousToken();
        }

        try {
            $ratingService->submitRating(
                $item->id,
                $validated['rating'],
                $validated['comment'] ?? null,
                $token
            );
        } catch (ValidationException $exception) {
            return back()->withErrors($exception->errors());
        }

        return back()
            ->with('success', 'Thank you! Your rating has been submitted.')
            ->withCookie(cookie(
                'anonymous_rating_token',
                $token,
                60 * 24 * 365,
                '/',
                null,
                false,
                true,
                false,
                'Lax'
            ));
    }
}
