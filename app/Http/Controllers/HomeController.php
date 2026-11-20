<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $todayMenu = DailyMenu::with([
            'dailyMenuItems.foodItem.category',
            'dailyMenuItems.ratings',
        ])
        ->whereDate('menu_date', today())
        ->first();

        return view('home', compact('todayMenu'));
    }
}