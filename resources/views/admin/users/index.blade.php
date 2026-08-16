@extends('admin.layout')
@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<div class="mb-3">
    <span style="color:rgba(255,255,255,0.4); font-size:0.85rem;">{{ $users->total() }} users total</span>
</div>

<div class="admin-card">
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr><th>Name</th><th>Email</th><th>Role</th><th>Subscription</th><th>Joined</th><th>Change Role</th></tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $currentRole = $user->roles->first()?->name ?? 'user';
                    $activeSub   = $user->subscriptions->first();
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}"
                           style="color:#fff; font-weight:600; text-decoration:none;">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td style="color:rgba(255,255,255,0.5); font-size:0.83rem;">{{ $user->email }}</td>
                    <td>
                        <span class="badge badge-role-{{ $currentRole }}">{{ $currentRole }}</span>
                    </td>
                    <td>
                        @if($activeSub)
                            <span class="badge badge-sub-active">Active</span>
                        @else
                            <span style="color:rgba(255,255,255,0.3); font-size:0.8rem;">—</span>
                        @endif
                    </td>
                    <td style="color:rgba(255,255,255,0.4); font-size:0.8rem;">
                        {{ $user->created_at?->format('d M Y') }}
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}"
                              class="d-flex gap-2 align-items-center">
                            @csrf @method('PATCH')
                            <select name="role" class="form-select form-select-dark form-select-sm"
                                    style="width:auto; min-width:90px;">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $currentRole === $role->name ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-admin-ghost btn btn-sm">Update</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; color:rgba(255,255,255,0.3); padding:2rem;">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
