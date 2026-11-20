<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'daily_menu_item_id',
        'rating',
        'comment',
        'anonymous_token_hash',
    ];

    public function dailyMenuItem(): BelongsTo
    {
        return $this->belongsTo(DailyMenuItem::class);
    }
}