<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeeklyMenu extends Model
{
    protected $fillable = [
        'day_of_week',
        'title',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function weeklyMenuItems(): HasMany
    {
        return $this->hasMany(WeeklyMenuItem::class);
    }

    public function dailyMenus(): HasMany
    {
        return $this->hasMany(DailyMenu::class);
    }

    public function suggestions(): HasMany
    {
        return $this->hasMany(MenuSuggestion::class);
    }
}
