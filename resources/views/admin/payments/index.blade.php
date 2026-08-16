@extends('admin.layout')
@section('title', 'Payments')
@section('page-title', 'Payments')

@section('content')
<div class="mb-3">
    <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">{{ $payments->total() }} payments total</span>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>User</th><th>Plan</th><th>Amount</th><th>Method</th><th>Status</th><th>Stripe Invoice</th><th>Paid At</th></tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td style="color:rgba(255,255,255,0.35);">#{{ $payment->id }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $payment->subscription?->user?->name ?? '—' }}</div>
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.35);">
                            {{ $payment->subscription?->user?->email }}
                        </div>
                    </td>
                    <td style="color:rgba(255,255,255,0.6);">
                        {{ $payment->subscription?->plan?->name ?? '—' }}
                    </td>
                    <td style="font-weight:600;">
                        {{ $payment->currency }} {{ number_format($payment->amount, 2) }}
                    </td>
                    <td style="color:rgba(255,255,255,0.5); font-size:0.83rem;">
                        {{ $payment->method ?? '—' }}
                    </td>
                    <td>
                        <span class="badge {{ $payment->status === 'paid' ? 'badge-sub-active' : 'badge-sub-cancelled' }}">
                            {{ $payment->status }}
                        </span>
                    </td>
                    <td style="font-size:0.75rem; color:rgba(255,255,255,0.35); font-family:monospace;">
                        {{ $payment->stripe_invoice_id ? Str::limit($payment->stripe_invoice_id, 20) : '—' }}
                    </td>
                    <td style="font-size:0.8rem; color:rgba(255,255,255,0.5);">
                        {{ $payment->paid_at?->format('d M Y') ?? $payment->created_at?->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:rgba(255,255,255,0.3); padding:2rem;">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
