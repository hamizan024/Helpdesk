<?php

namespace App\Providers;

use App\Listeners\RecordLoginHistory;
use App\Models\Comment;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\CommentPolicy;
use App\Policies\DashboardPolicy;
use App\Policies\TicketPolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

/**
 * Bootstraps application-wide services: pagination style, model policies, named gates, and auth event listeners.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Gate::policy(Ticket::class, TicketPolicy::class);
        Gate::policy(Comment::class, CommentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('view-dashboard', [DashboardPolicy::class, 'view']);

        Event::listen(Login::class, [RecordLoginHistory::class, 'handleLogin']);
        Event::listen(Failed::class, [RecordLoginHistory::class, 'handleFailed']);
    }
}