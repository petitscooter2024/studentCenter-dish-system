<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use App\Models\Rating;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $totalMenus = DailyMenu::count();

        $totalRatings = Rating::count();

        $totalStaff = User::where('role', 'staff')->count();

        $recentRatings = Rating::with([
            'dailyMenuItem.foodItem',
            'dailyMenuItem.dailyMenu',
        ])
        ->latest()
        ->take(5)
        ->get();

        return view('admin.dashboard', compact(
            'totalMenus',
            'totalRatings',
            'totalStaff',
            'recentRatings'
        ));
    }
}