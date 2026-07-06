<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Displays the authenticated user's paginated login history.
 */
class LoginHistoryController extends Controller
{
    public function index(Request $request): View
    {
        $histories = $request->user()
            ->loginHistories()
            ->latest()
            ->paginate(15);

        return view('profile.login-history', compact('histories'));
    }
}