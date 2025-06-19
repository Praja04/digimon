<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardMakro extends Controller
{
    //
    public function dashboard()
    {
        // if (!Session::has('role')) {
        //     return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        // }

        return view('supervisor.makro.dashboard');
    }
}
