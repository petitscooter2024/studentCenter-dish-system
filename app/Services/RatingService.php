<?php

namespace App\Services;

use App\Models\Rating;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RatingService
{
    public function submitRating(
        int $dailyMenuItemId,
        int $rating,
        ?string $comment,
        string $token
    ): Rating {
        $tokenHash = hash('sha256', $token);

        $alreadyRated = Rating::where('daily_menu_item_id', $dailyMenuItemId)
            ->where('anonymous_token_hash', $tokenHash)
            ->exists();

        if ($alreadyRated) {
            throw ValidationException::withMessages([
                'rating' => 'You have already rated this dish.',
            ]);
        }

        return Rating::create([
            'daily_menu_item_id' => $dailyMenuItemId,
            'rating' => $rating,
            'comment' => $comment,
            'anonymous_token_hash' => $tokenHash,
        ]);
    }

    public function generateAnonymousToken(): string
    {
        return Str::random(64);
    }
}