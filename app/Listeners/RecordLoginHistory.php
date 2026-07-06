<?php

namespace App\Listeners;

use App\Models\LoginHistory;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;

/**
 * Records each login attempt to login_histories for security auditing.
 */
class RecordLoginHistory
{
    /**
     * Log a successful authentication event.
     */
    public function handleLogin(Login $event): void
    {
        LoginHistory::create([
            'user_id'    => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status'     => 'success',
        ]);
    }

    /**
     * Log a failed authentication event.
     *
     * Only recorded when a matching user account was found ($event->user is not null),
     * so enumeration attempts against non-existent addresses are not logged.
     */
    public function handleFailed(Failed $event): void
    {
        if ($event->user === null) {
            return;
        }

        LoginHistory::create([
            'user_id'    => $event->user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status'     => 'failed',
        ]);
    }
}