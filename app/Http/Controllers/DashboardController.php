<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    /**
     * Render the appropriate dashboard according to the user's role.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasFullAccess()) {
            $data = $this->dashboardService->getDirectorDashboardData();

            return view('dashboard.director', $data);
        }

        if ($user->isTeamLead()) {
            $data = $this->dashboardService->getManagerDashboardData($user);

            return view('dashboard.manager', $data);
        }

        // Staff dashboard
        $data = $this->dashboardService->getStaffDashboardData($user);

        return view('dashboard.staff', $data);
    }
}
