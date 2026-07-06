<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Lists and revokes the authenticated user's active sessions.
 */
class SessionController extends Controller
{
    /**
     * Display all active sessions for the current user.
     */
    public function index(Request $request): View
    {
        $currentId = $request->session()->getId();

        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(fn ($s) => $this->formatSession($s, $currentId));

        return view('profile.sessions', compact('sessions'));
    }

    /**
     * Revoke a specific session owned by the authenticated user.
     */
    public function destroy(Request $request, string $session): RedirectResponse
    {
        DB::table('sessions')
            ->where('id', $session)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('status', 'session-revoked');
    }

    /**
     * Revoke all sessions except the currently active one.
     */
    public function destroyOthers(Request $request): RedirectResponse
    {
        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('status', 'other-sessions-revoked');
    }

    private function parseUserAgent(string $ua): array
    {
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

    private function formatSession(object $session, string $currentId): object
    {
        $ua = $this->parseUserAgent($session->user_agent ?? '');

        return (object) [
            'id'         => $session->id,
            'isCurrent'  => $session->id === $currentId,
            'ip_address' => $session->ip_address,
            'browser'    => $ua['browser'],
            'os'         => $ua['os'],
            'lastActive' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
        ];
    }
}