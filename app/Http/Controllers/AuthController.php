<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the combined login / register page.
     */
    public function showLoginForm(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect('/');
        }

        // Laravel's auth middleware stores the intended URL as 'url.intended' in the session.
        // We read it and pass it to the view so the form can embed it in a hidden field.
        // session()->pull() removes it so it does not interfere with redirect()->intended() later.
        $intended = session()->pull('url.intended', $request->query('intended', '/'));

        return view('auth.login', compact('intended'));
    }

    /**
     * Handle a login request.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect to the intended URL that was embedded in the form.
            // We use a simple redirect() rather than redirect()->intended() to avoid
            // any stale session state from a previous request.
            $intended = $request->input('intended', '/');
            $intended = (empty($intended) || $intended === 'null') ? '/' : $intended;

            return redirect($intended);
        }

        return back()
            ->withInput($request->only('email', 'intended'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'              => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]);

        $userRole = \App\Models\Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $intended = $request->input('intended', '/');
        $intended = (empty($intended) || $intended === 'null') ? '/' : $intended;

        return redirect($intended);
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
