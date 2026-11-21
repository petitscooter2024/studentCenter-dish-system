<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(MenuService $menuService): View
    {
        $todayMenu = $menuService->getMenuForDate(
            today()->toDateString()
        );

        return view('home', compact('todayMenu'));
    }
}