<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a single login attempt (successful or failed) for audit purposes.
 *
 * @property int         $id
 * @property int         $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string      $status      'success'|'failed'
 * @property \Carbon\Carbon $created_at
 */
class LoginHistory extends Model
{
    /**
     * Login history records are append-only — never update existing rows.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getBrowserAttribute(): string
    {
        return $this->parseUserAgent()['browser'];
    }

    public function getOsAttribute(): string
    {
        return $this->parseUserAgent()['os'];
    }

    private function parseUserAgent(): array
    {
        $ua = $this->user_agent ?? '';

        $browser = 'Unknown';
        if (str_contains($ua, 'Edg')) {
            $browser = 'Edge';
        } elseif (str_contains($ua, 'OPR') || str_contains($ua, 'Opera')) {
            $browser = 'Opera';
        } elseif (str_contains($ua, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($ua, 'Chrome')) {
            $browser = 'Chrome';
        } elseif (str_contains($ua, 'Safari')) {
            $browser = 'Safari';
        }

        $os = 'Unknown';
        if (str_contains($ua, 'Android')) {
            $os = 'Android';
        } elseif (str_contains($ua, 'iPhone') || str_contains($ua, 'iPad')) {
            $os = 'iOS';
        } elseif (str_contains($ua, 'Windows')) {
            $os = 'Windows';
        } elseif (str_contains($ua, 'Macintosh') || str_contains($ua, 'Mac OS X')) {
            $os = 'macOS';
        } elseif (str_contains($ua, 'Linux')) {
            $os = 'Linux';
        }

        return ['browser' => $browser, 'os' => $os];
    }
}