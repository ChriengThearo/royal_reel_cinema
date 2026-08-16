<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_movies'       => Movie::count(),
            'total_users'        => User::count(),
            'active_subs'        => Subscription::where('status', 'active')->where('end_date', '>', now())->count(),
            'total_revenue'      => Payment::where('status', 'paid')->sum('amount'),
        ];

        $recentPayments = Payment::with(['subscription.user', 'subscription.plan'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPayments'));
    }
}
