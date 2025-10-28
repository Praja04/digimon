<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginForm()
    {
        if (Session::has('role')) {
            return redirect()->back();
        }
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

            Session::put('id', $user->id);
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
            'role' => 'required|in:analis,foreman,supervisor,dept_head',
            'role_group' => 'required'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']), // Pakai bcrypt untuk hashing password
            'role' => $validated['role'],
            'role_group' => $validated['role_group'],
        ]);

        return response()->json(['success' => 'User created successfully.']);
    }

    private function redirectByRole($role, $roleGroup = null)
    {
        return match ($role) {
            'analis' => match ($roleGroup) {
                'makro', 'mikro' => 'ggaggas.menu',
                'rmpm' => 'dataRM_analis',
                'field' => 'blending_analis.menu',
                default => 'dashboard', // fallback kalau role_group tidak dikenali
            },
            'produksi' => 'productionbatch.menu',
            'foreman' => 'dataRM_foreman',
            'supervisor' => 'dataRM_supervisor',
            'dept_head' => 'dept_head.dashboard',
            default => 'dashboard',
        };
    }

    public function manage_user()
    {
        if (!Session::has('role') || Session::get('role') !== 'supervisor') {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        return view('supervisor.manage_user');
    }

    public function getUsers()
    {
        $users = User::select(
            'id',
            'email',
            'name',
            'email',
            'role',
            'role_group',
        )->get();

        return response()->json($users);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
            'role_group' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $user = User::findOrFail($id);


        // Update data user
        $user->update([
            'name' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'role_group' => $request->role_group,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        return response()->json(['success' => 'User updated successfully.']);
    }

    // Menghapus data pengguna
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully.']);
    }
}
