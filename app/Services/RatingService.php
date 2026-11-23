<?php

namespace App\Services;

use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RatingService
{
    public function submitRating(
        int $weeklyMenuItemId,
        int $rating,
        ?string $comment,
        string $token
    ): Rating {
        return DB::transaction(function () use (
            $weeklyMenuItemId,
            $rating,
            $comment,
            $token
        ) {
            $tokenHash = hash('sha256', $token);

            $alreadyRated = Rating::where(
                'weekly_menu_item_id',
                $weeklyMenuItemId
            )
                ->where('anonymous_token_hash', $tokenHash)
                ->exists();

            if ($alreadyRated) {
                throw ValidationException::withMessages([
                    'rating' => 'You have already rated this dish.',
                ]);
            }

            return Rating::create([
                'weekly_menu_item_id' => $weeklyMenuItemId,
                'rating' => $rating,
                'comment' => $comment,
                'anonymous_token_hash' => $tokenHash,
            ]);
        });
    }

    public function generateAnonymousToken(): string
    {
        return Str::random(64);
    }
}