<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\User;
use App\Models\UserApplication;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Affiche le dashboard utilisateur (étudiant/user)
     */
    public function index(): View
    {
        $user = auth()->user();

        $myApplications = UserApplication::where('user_id', $user->id)
            ->with(['offer', 'offer.company'])
            ->latest()
            ->take(5)
            ->get();

        $recentOffers = Offer::with(['company', 'region'])
            ->active()
            ->latest()
            ->take(5)
            ->get();

        $totalApplications = UserApplication::where('user_id', $user->id)->count();
        $pendingApplications = UserApplication::where('user_id', $user->id)->where('status', 'pending')->count();
        $acceptedApplications = UserApplication::where('user_id', $user->id)->where('status', 'accepted')->count();
        $rejectedApplications = UserApplication::where('user_id', $user->id)->where('status', 'rejected')->count();

        $applicationsThisMonth = UserApplication::where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $applicationsLastMonth = UserApplication::where('user_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();

        $applicationsByMonth = UserApplication::where('user_id', $user->id)
            ->selectRaw("strftime('%m', created_at) as month, COUNT(*) as count")
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $months = [];
        $applicationData = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::create(null, $i, 1)->format('M');
            $applicationData[] = $applicationsByMonth[$i] ?? 0;
        }

        $stats = [
            'totalApplications' => $totalApplications,
            'pendingApplications' => $pendingApplications,
            'acceptedApplications' => $acceptedApplications,
            'rejectedApplications' => $rejectedApplications,
            'applicationsThisMonth' => $applicationsThisMonth,
            'applicationsLastMonth' => $applicationsLastMonth,
            'totalOffers' => Offer::active()->count(),
            'totalCompanies' => \App\Models\Company::count(),
        ];

        $chartData = [
            'labels' => $months,
            'data' => $applicationData,
        ];

        return view('dashboard.user', [
            'myApplications' => $myApplications,
            'recentOffers' => $recentOffers,
            'stats' => $stats,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Affiche le dashboard manager
     */
    public function manager(): View
    {
        $user = auth()->user();

        $myCompany = $user->company;

        if ($myCompany) {
            $myOffers = Offer::where('company_id', $myCompany->id)
                ->with(['company', 'region'])
                ->latest()
                ->take(5)
                ->get();

            $applications = UserApplication::whereHas('offer', function ($query) use ($myCompany) {
                $query->where('company_id', $myCompany->id);
            })->with(['offer', 'user'])->latest()->take(10)->get();

            $stats = [
                'totalOffers' => Offer::where('company_id', $myCompany->id)->count(),
                'activeOffers' => Offer::where('company_id', $myCompany->id)->active()->count(),
                'totalApplications' => $applications->count(),
                'pendingApplications' => $applications->where('status', 'pending')->count(),
            ];
        } else {
            $myOffers = collect([]);
            $applications = collect([]);
            $stats = [
                'totalOffers' => 0,
                'activeOffers' => 0,
                'totalApplications' => 0,
                'pendingApplications' => 0,
            ];
        }

        return view('dashboard.manager', [
            'myCompany' => $myCompany,
            'myOffers' => $myOffers,
            'applications' => $applications,
            'stats' => $stats,
        ]);
    }
}
