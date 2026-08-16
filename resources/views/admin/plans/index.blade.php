@extends('admin.layout')
@section('title', 'Plans')
@section('page-title', 'Subscription Plans')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">{{ $plans->total() }} plans</span>
    <a href="{{ route('admin.plans.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Plan
    </a>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>Name</th><th>Price</th><th>Cycle</th><th>Quality</th><th>Screens</th><th>Active</th><th></th></tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td style="font-weight:600;">{{ $plan->name }}</td>
                    <td>{{ $plan->currency }} {{ number_format($plan->price, 2) }}</td>
                    <td style="color:rgba(255,255,255,0.5);">{{ $plan->billing_cycle }}</td>
                    <td><span class="badge badge-role-admin">{{ $plan->max_quality }}</span></td>
                    <td style="color:rgba(255,255,255,0.5);">{{ $plan->screens_allowed }}</td>
                    <td>
                        @if($plan->is_active)
                            <span class="badge badge-sub-active">Active</span>
                        @else
                            <span class="badge badge-sub-cancelled">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-admin-ghost btn">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}"
                                  onsubmit="return confirm('Delete plan \'{{ addslashes($plan->name) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-admin-danger btn"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="text-align:center; color:rgba(255,255,255,0.3); padding:2rem;">No plans yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $plans->links() }}</div>
@endsection
