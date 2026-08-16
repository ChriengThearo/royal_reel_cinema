<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'subscriptions' => fn ($q) => $q->where('status', 'active')->where('end_date', '>', now())])
            ->latest()
            ->paginate(25);
        $roles = Role::orderBy('name')->get();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'subscriptions.plan', 'ratings']);
        return view('admin.users.show', compact('user'));
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate(['role' => ['required', 'in:admin,user']]);

        $role = Role::where('name', $request->role)->firstOrFail();

        // Replace all current roles with only the selected one
        $user->roles()->sync([$role->id]);

        return back()->with('success', "Role updated to \"{$request->role}\" for {$user->name}.");
    }
}
