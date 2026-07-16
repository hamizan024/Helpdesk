<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Master\CategoryController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\PriorityController;
use App\Http\Controllers\Master\StatusController;
use App\Http\Controllers\Master\TechnicianController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Profile\LoginHistoryController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Profile\SessionController;
use App\Http\Controllers\Ticket\AttachmentController;
use App\Http\Controllers\Ticket\CommentController;
use App\Http\Controllers\Ticket\TicketController;
use App\Http\Controllers\Ticket\WatcherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Static ticket routes must precede Route::resource to avoid {ticket} swallowing them
    Route::get('/tickets/export', [TicketController::class, 'export'])->name('tickets.export');

    Route::resource('tickets', TicketController::class);

    // Additional ticket actions
    Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolve'])->name('tickets.resolve');
    Route::get('/tickets/{ticket}/print', [TicketController::class, 'print'])->name('tickets.print');

    // Comments
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::patch('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Attachments
    Route::post('/tickets/{ticket}/attachments', [AttachmentController::class, 'store'])->name('tickets.attachments.store');
    Route::get('/tickets/{ticket}/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('tickets.attachments.download');
    Route::delete('/tickets/{ticket}/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('tickets.attachments.destroy');

    // Watchers
    Route::post('/tickets/{ticket}/watch', [WatcherController::class, 'store'])->name('tickets.watch');
    Route::delete('/tickets/{ticket}/watch', [WatcherController::class, 'destroy'])->name('tickets.unwatch');

    // Notifications
    Route::post('/notifications/read-all', function (Request $request) {
        $request->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    })->name('notifications.read-all');

    Route::post('/notifications/{id}/read', function (Request $request, string $id) {
        $request->user()->notifications()->where('id', $id)->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    })->name('notifications.read');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    Route::delete('/profile/avatar', [ProfileController::class, 'destroyAvatar'])->name('profile.avatar.destroy');

    Route::get('/profile/sessions', [SessionController::class, 'index'])->name('profile.sessions');
    Route::delete('/profile/sessions/others', [SessionController::class, 'destroyOthers'])->name('profile.sessions.others');
    Route::delete('/profile/sessions/{session}', [SessionController::class, 'destroy'])->name('profile.sessions.destroy');

    Route::get('/profile/login-history', [LoginHistoryController::class, 'index'])->name('profile.login-history');

    // API Token management (from profile)
    Route::post('/profile/tokens', function (Request $request) {
        $request->validate(['token_name' => ['required', 'string', 'max:100']]);
        $token = $request->user()->createToken($request->token_name);

        return back()->with('new_token', $token->plainTextToken)
            ->with('success', 'API token berhasil dibuat. Copy dan simpan — hanya tampil sekali.');
    })->name('profile.tokens.store');

    Route::delete('/profile/tokens/{tokenId}', function (Request $request, int $tokenId) {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return back()->with('success', 'Token berhasil dihapus.');
    })->name('profile.tokens.destroy');

    // Master Data — admin only
    Route::middleware('admin')->prefix('master')->name('master.')->group(function () {
        Route::resource('departments', DepartmentController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('priorities', PriorityController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('statuses', StatusController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('technicians', [TechnicianController::class, 'index'])->name('technicians.index');
        Route::patch('technicians/{technician}', [TechnicianController::class, 'update'])->name('technicians.update');

        Route::resource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
    });

});

require __DIR__.'/auth.php';
