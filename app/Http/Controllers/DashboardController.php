<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    //
    public function dashboard_gga_ggas()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_gga_ggas');
    }
    public function dashboard_blending_awal()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_blending_awal');
    }

    public function dashboard_blending_after()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_blending_after');
    }
    public function dashboard_rm()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_rm');
    }
    public function dashboard_monitoring_turun()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_monitoring_turun');
    }
    public function dashboard_monitoring_storage()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_monitoring_storage');
    }

    public function dashboard_blending_after_mikro()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_mikro_after_adjust');
    }

    public function dashboard_monitoring_storage_mikro()
    {
        if (!Session::has('role')) {
            return redirect('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        return view('dashboard.dashboard_mikro_monitoring_storage');
    }
}

