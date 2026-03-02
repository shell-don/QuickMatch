<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalRoles = Role::count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $userStats = $this->getUserStats();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalRoles' => $totalRoles,
            'newUsersThisMonth' => $newUsersThisMonth,
            'userStats' => $userStats,
        ]);
    }

    private function getUserStats(): array
    {
        $stats = User::selectRaw('strftime("%m", created_at) as month, COUNT(*) as count')
            ->whereRaw("strftime('%Y', created_at) >= ?", [now()->subYear()->format('Y')])
            ->groupBy('month')
            ->get();

        $months = [];
        $counts = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthIndex = (int) $date->format('n');
            $monthName = $date->format('M');

            $months[] = $monthName;
            $monthStr = str_pad((string) $monthIndex, 2, '0', STR_PAD_LEFT);
            $count = $stats->firstWhere('month', $monthStr);
            $counts[] = $count ? (int) $count->count : 0;
        }

        return [
            'labels' => $months,
            'data' => $counts,
        ];
    }
}
