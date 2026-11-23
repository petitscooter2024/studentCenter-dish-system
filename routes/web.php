
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
use App\Http\Controllers\StaffManagementController;

use App\Http\Controllers\Admin\WeeklyMenuController;
use App\Http\Controllers\Admin\MenuSuggestionController as AdminMenuSuggestionController;
use App\Http\Controllers\Staff\MenuSuggestionController as StaffMenuSuggestionController;

// PUBLIC PAGES

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');

Route::post('/ratings', [RatingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('ratings.store');

// AUTHENTICATION

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ADMIN PAGES

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/ratings', [AdminController::class, 'ratings'])
            ->name('ratings.index');

        Route::resource('/categories', CategoryController::class)
            ->except(['create', 'edit', 'show']);

        Route::resource('/food-items', FoodItemController::class)
            ->except(['create', 'edit', 'show']);

        Route::get('/weekly-menus', [WeeklyMenuController::class, 'index'])
            ->name('weekly-menus.index');

        Route::post('/weekly-menus', [WeeklyMenuController::class, 'saveMenu'])
            ->name('weekly-menus.store');

        Route::post('/weekly-menus/{weeklyMenu}/items', [WeeklyMenuController::class, 'addItem'])
            ->name('weekly-menus.items.store');

        Route::delete('/weekly-menus/{weeklyMenu}/items/{item}', [WeeklyMenuController::class, 'removeItem'])
            ->name('weekly-menus.items.destroy');

        Route::get('/suggestions', [AdminMenuSuggestionController::class, 'index'])
            ->name('suggestions.index');

        Route::post('/suggestions/{suggestion}/approve', [AdminMenuSuggestionController::class, 'approve'])
            ->name('suggestions.approve');

        Route::post('/suggestions/{suggestion}/reject', [AdminMenuSuggestionController::class, 'reject'])
            ->name('suggestions.reject');

        Route::get('/staff', [StaffManagementController::class, 'index'])
            ->name('staff.index');

        Route::post('/staff', [StaffManagementController::class, 'store'])
            ->name('staff.store');

        Route::delete('/staff/{user}', [StaffManagementController::class, 'destroy'])
            ->name('staff.destroy');
    });

// STAFF PAGES

Route::middleware(['auth', 'role:staff'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        Route::get('/dashboard', [StaffController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/daily-menu-items/{item}/availability', [StaffController::class, 'updateAvailability'])
            ->name('daily-menu-items.availability');

        Route::get('/suggestions', [StaffMenuSuggestionController::class, 'index'])
            ->name('suggestions.index');

        Route::post('/suggestions', [StaffMenuSuggestionController::class, 'store'])
            ->name('suggestions.store');
    });
