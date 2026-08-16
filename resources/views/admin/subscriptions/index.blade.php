@extends('admin.layout')
@section('title', 'Subscriptions')
@section('page-title', 'Subscriptions')

@section('content')
<div class="mb-3">
    <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">{{ $subscriptions->total() }} subscriptions total</span>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>User</th><th>Plan</th><th>Status</th><th>Start</th><th>End</th><th>Auto-Renew</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td style="color:rgba(255,255,255,0.35);">#{{ $sub->id }}</td>
                    <td>
                        <div style="font-weight:600;">{{ $sub->user?->name ?? '—' }}</div>
                        <div style="font-size:0.75rem; color:rgba(255,255,255,0.35);">{{ $sub->user?->email }}</div>
                    </td>
                    <td>{{ $sub->plan?->name ?? '—' }}</td>
                    <td>
                        <span class="badge badge-sub-{{ $sub->status }}">{{ $sub->status }}</span>
                    </td>
                    <td style="font-size:0.8rem; color:rgba(255,255,255,0.5);">
                        {{ $sub->start_date?->format('d M Y') }}
                    </td>
                    <td style="font-size:0.8rem; color:rgba(255,255,255,0.5);">
                        {{ $sub->end_date?->format('d M Y') }}
                    </td>
                    <td style="font-size:0.8rem; color:rgba(255,255,255,0.5);">
                        {{ $sub->auto_renew ? 'Yes' : 'No' }}
                    </td>
                    <td>
                        @if($sub->status !== 'cancelled')
                        <form method="POST" action="{{ route('admin.subscriptions.cancel', $sub) }}"
                              onsubmit="return confirm('Cancel subscription #{{ $sub->id }}?')">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-admin-danger btn btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Cancel
                            </button>
                        </form>
                        @else
                            <span style="color:rgba(255,255,255,0.2); font-size:0.8rem;">Cancelled</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center; color:rgba(255,255,255,0.3); padding:2rem;">No subscriptions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $subscriptions->links() }}</div>
@endsection
