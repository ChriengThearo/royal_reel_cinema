<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['subscription.user', 'subscription.plan'])
            ->latest('created_at')
            ->paginate(25);
        return view('admin.payments.index', compact('payments'));
    }
}
