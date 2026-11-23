<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyMenuItem extends Model
{
    protected $fillable = [
        'daily_menu_id',
        'food_item_id',
        'price',
        'is_available',
        'is_visible',
        'availability_override',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_available' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function dailyMenu(): BelongsTo
    {
        return $this->belongsTo(DailyMenu::class);
    }

    public function foodItem(): BelongsTo
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}