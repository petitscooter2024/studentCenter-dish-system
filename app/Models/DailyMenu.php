<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyMenu extends Model
{
    protected $fillable = [
        'menu_date',
        'title',
        'publication_status',
        'weekly_menu_id',
    ];

    protected function casts(): array
    {
        return [
            'menu_date' => 'date',
        ];
    }

    public function dailyMenuItems(): HasMany
    {
        return $this->hasMany(DailyMenuItem::class);
    }

    public function weeklyMenu(): BelongsTo
    {
        return $this->belongsTo(WeeklyMenu::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(MenuSuggestion::class);
    }
}
