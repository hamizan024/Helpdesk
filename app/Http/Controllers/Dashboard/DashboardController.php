<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(): View
    {
        $this->authorize('view-dashboard');

        $user = auth()->user();

        return view('dashboard.index', array_merge(
            $this->dashboardService->getStats($user),
            ['recentTickets' => $this->dashboardService->getRecentTickets($user)]
        ));
    }
}
