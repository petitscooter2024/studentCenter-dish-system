<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\DailyMenuController;
use App\Http\Controllers\StaffManagementController;

// Public pages
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'index'])
    ->name('menu.index');

Route::get('/menu/{dailyMenu}', [MenuController::class, 'show'])
    ->name('menu.show');

Route::post('/ratings', [RatingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('ratings.store');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Admin pages
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::resource('/categories', CategoryController::class)
            ->except(['create', 'edit', 'show']);

        Route::resource('/food-items', FoodItemController::class)
            ->except(['create', 'edit', 'show']);

        Route::get('/daily-menus', [DailyMenuController::class, 'index'])
            ->name('daily-menus.index');

        Route::post('/daily-menus', [DailyMenuController::class, 'store'])
            ->name('daily-menus.store');

        Route::post('/daily-menus/{dailyMenu}/items', [DailyMenuController::class, 'addItem'])
            ->name('daily-menus.items.store');

        Route::put('/menu-items/{dailyMenuItem}', [DailyMenuController::class, 'updateItem'])
            ->name('menu-items.update');

        Route::delete('/menu-items/{dailyMenuItem}', [DailyMenuController::class, 'removeItem'])
            ->name('menu-items.destroy');

        Route::get('/staff', [StaffManagementController::class, 'index'])
            ->name('staff.index');

        Route::post('/staff', [StaffManagementController::class, 'store'])
            ->name('staff.store');

        Route::delete('/staff/{user}', [StaffManagementController::class, 'destroy'])
            ->name('staff.destroy');
    });

// Staff pages
Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/daily-menu', [StaffController::class, 'storeMenu'])
            ->name('daily-menu.store');
    });