<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\PriorityController;
use App\Http\Controllers\Master\StatusController;
use App\Http\Controllers\Profile\LoginHistoryController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SessionController;
use App\Http\Controllers\Ticket\CommentController;
use App\Http\Controllers\Ticket\TicketController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('tickets', TicketController::class);

    // Profile — information & password
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    // Profile — avatar
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar');

    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])
        ->name('profile.avatar.destroy');

    // Profile — active sessions
    Route::get('/profile/sessions', [SessionController::class, 'index'])
        ->name('profile.sessions');

    Route::delete('/profile/sessions/others', [SessionController::class, 'destroyOthers'])
        ->name('profile.sessions.others');

    Route::delete('/profile/sessions/{session}', [SessionController::class, 'destroy'])
        ->name('profile.sessions.destroy');

    // Profile — login history
    Route::get('/profile/login-history', [LoginHistoryController::class, 'index'])
        ->name('profile.login-history');

    // Master Data (admin only — enforced in MasterDataController constructor)
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('categories',  CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('priorities',  PriorityController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('statuses',    StatusController::class)->only(['index', 'store', 'update', 'destroy']);
    });

    // Comments
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

});

require __DIR__.'/auth.php';