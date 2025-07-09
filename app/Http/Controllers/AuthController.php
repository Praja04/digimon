<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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

            $user = Auth::user();

            Session::put('username', $user->name);
            Session::put('role', $user->role);
            Session::put('role_group', $user->role_group);
            Cookie::queue('username', $user->name, 60);

            // Kirim role group sebagai parameter
            $redirectRoute = $this->redirectByRole($user->role, $user->role_group);

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

    private function redirectByRole($role, $roleGroup = null)
    {
        return match ($role) {
            'analis' => match ($roleGroup) {
                'makro', 'mikro' => 'ggaggas.menu',
                'rmpm' => 'rmpm.pilihJenisGula',
                default => 'dashboard', // fallback kalau role_group tidak dikenali
            },
            'produksi' => 'productionbatch.menu',
            'foreman' => 'rmpm_foreman.menu',
            'supervisor' => 'rmpm_supervisor.menu',
            'dept_head' => 'dept_head.dashboard',
            default => 'dashboard',
        };
    }
}
