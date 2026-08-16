<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->paginate(25);
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled', 'auto_renew' => false]);
        return back()->with('success', "Subscription #{$subscription->id} cancelled.");
    }
}
