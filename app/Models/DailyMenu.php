<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyMenu extends Model
{
    protected $fillable = [
        'menu_date',
        'title',
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
}