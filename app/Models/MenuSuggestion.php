<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuSuggestion extends Model
{
    protected $fillable = [
        'daily_menu_id',
        'weekly_menu_id',
        'weekly_menu_item_id',
        'daily_menu_item_id',
        'food_item_id',
        'category_id',
        'price',
        'action',
        'reason',
        'status',
        'submitted_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'reviewed_at' => 'datetime',
        ];
    }

    public function dailyMenu(): BelongsTo
    {
        return $this->belongsTo(DailyMenu::class);
    }

    public function weeklyMenu(): BelongsTo
    {
        return $this->belongsTo(WeeklyMenu::class);
    }

    public function weeklyMenuItem(): BelongsTo
    {
        return $this->belongsTo(WeeklyMenuItem::class);
    }

    public function dailyMenuItem(): BelongsTo
    {
        return $this->belongsTo(DailyMenuItem::class);
    }

    public function foodItem(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
