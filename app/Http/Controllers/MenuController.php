<?php

namespace App\Http\Controllers;

use App\Models\DailyMenu;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->query('date', today()->toDateString());

        $menu = DailyMenu::with([
            'dailyMenuItems.foodItem.category',
            'dailyMenuItems.ratings',
        ])
        ->whereDate('menu_date', $date)
        ->first();

        return view('menu.index', compact('menu', 'date'));
    }

    public function show(DailyMenu $dailyMenu): View
    {
        $dailyMenu->load([
            'dailyMenuItems.foodItem.category',
            'dailyMenuItems.ratings',
        ]);

        return view('menu.show', compact('dailyMenu'));
    }
}