<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\FoodItem;
use App\Models\DailyMenu;
use App\Models\DailyMenuItem;
use App\Models\Rating;
use App\Services\RatingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_an_anonymous_rating(): void
    {
        $this->seed();

        $menuItem = DailyMenuItem::firstOrFail();

        $service = app(RatingService::class);

        $rating = $service->submitRating(
            $menuItem->id,
            5,
            'Good food',
            'student-token-1'
        );

        $this->assertDatabaseHas('ratings', [
            'id' => $rating->id,
            'daily_menu_item_id' => $menuItem->id,
            'rating' => 5,
            'comment' => 'Good food',
        ]);
    }

    public function test_same_anonymous_token_cannot_rate_twice(): void
    {
        $this->seed();

        $menuItem = DailyMenuItem::firstOrFail();

        $service = app(RatingService::class);

        $service->submitRating(
            $menuItem->id,
            4,
            null,
            'student-token-2'
        );

        $this->expectException(ValidationException::class);

        $service->submitRating(
            $menuItem->id,
            3,
            null,
            'student-token-2'
        );
    }

    public function test_different_anonymous_tokens_can_rate_same_dish(): void
    {
        $this->seed();

        $menuItem = DailyMenuItem::firstOrFail();

        $service = app(RatingService::class);

        $service->submitRating($menuItem->id, 4, null, 'token-A');
        $service->submitRating($menuItem->id, 5, null, 'token-B');

        $this->assertDatabaseCount('ratings', 2);
    }
}
