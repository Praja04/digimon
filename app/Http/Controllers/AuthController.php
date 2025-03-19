<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil role user
            $user = Auth::user();
            $redirectRoute = $this->redirectByRole($user->role);

            return response()->json(['redirect' => route($redirectRoute)]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:analis,foreman,supervisor,dept_head'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Pakai bcrypt untuk hashing password
            'role' => $validated['role']
        ]);

        Auth::login($user);
        return redirect()->route($this->redirectByRole($user->role));
    }

    private function redirectByRole($role)
    {
        return match ($role) {
            'analis' => 'analis.dashboard',
            'foreman' => 'foreman.dashboard',
            'supervisor' => 'supervisor.dashboard',
            'dept_head' => 'dept_head.dashboard',
            default => 'dashboard',
        };
    }
}
