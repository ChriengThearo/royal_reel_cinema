@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
{{-- Stat cards --}}
<div class="row g-3 mb-4">
    @foreach([
        ['icon'=>'bi-play-circle','label'=>'Total Movies',       'value'=> $stats['total_movies'],  'color'=>'rgba(255,255,255,0.7)'],
        ['icon'=>'bi-people',     'label'=>'Total Users',         'value'=> $stats['total_users'],   'color'=>'rgba(255,255,255,0.7)'],
        ['icon'=>'bi-credit-card','label'=>'Active Subscriptions','value'=> $stats['active_subs'],   'color'=>'#5cb85c'],
        ['icon'=>'bi-cash-stack', 'label'=>'Total Revenue',       'value'=>'$'.number_format($stats['total_revenue'],2),'color'=>'rgba(255,215,0,0.9)'],
    ] as $card)
    <div class="col-6 col-lg-3">
        <div class="admin-card p-3 d-flex align-items-center gap-3">
            <i class="bi {{ $card['icon'] }}" style="font-size:1.8rem; color:{{ $card['color'] }};"></i>
            <div>
                <div style="font-size:1.4rem; font-weight:800;">{{ $card['value'] }}</div>
                <div style="font-size:0.75rem; color:rgba(255,255,255,0.4);">{{ $card['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Recent payments --}}
<div class="admin-card">
    <div class="admin-card-header">Recent Payments</div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th><th>User</th><th>Plan</th><th>Amount</th><th>Status</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $p)
                <tr>
                    <td style="color:rgba(255,255,255,0.35);">#{{ $p->id }}</td>
                    <td>{{ $p->subscription->user->name ?? '—' }}</td>
                    <td>{{ $p->subscription->plan->name ?? '—' }}</td>
                    <td>{{ $p->currency }} {{ number_format($p->amount, 2) }}</td>
                    <td>
                        <span class="badge {{ $p->status === 'paid' ? 'badge-sub-active' : 'badge-sub-cancelled' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td style="color:rgba(255,255,255,0.4); font-size:0.8rem;">
                        {{ $p->created_at?->format('d M Y') }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="color:rgba(255,255,255,0.3); text-align:center; padding:2rem;">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
