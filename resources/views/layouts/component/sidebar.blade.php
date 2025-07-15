<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="#" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" alt="" height="25">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" alt="" height="100">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                @if(Session::get('role') === 'dept_head')
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/rmpm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/productionbatch')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                    </a>
                </li>
                @elseif(Session::get('role') === 'supervisor')
                <li class="menu-title"><span data-key="t-menu">Dashboard</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#DashboardProses" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="DashboardProses">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-dashboards">Dashboard Makro</span>
                    </a>
                    <div class="collapse menu-dropdown" id="DashboardProses">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{url('supervisor/ggaggas/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis GGA & GGAS</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('supervisor/blending/awal/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Blending Awal</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('supervisor/blending/adjust/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Blending After Adjust</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('supervisor/monitoring/blending/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Monitoring Turun Blending</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('supervisor/monitoring/storage/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Monitoring Storage</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#Dashboardmikro" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="Dashboardmikro">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-dashboards">Dashboard Mikro</span>
                    </a>
                    <div class="collapse menu-dropdown" id="Dashboardmikro">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics">Analisis Blending After Adjust</a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics">Analisis Monitoring Storage</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('supervisor/rmpm/dashboard')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Dashboard RMPM</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('supervisor/rmpm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('supervisor/ggaggas/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">GGA & GGAS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('supervisor/blending/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Blending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('supervisor/monitoring/blending/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Monitoring Turun Blending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Scan</span>
                    </a>
                </li>
                @elseif(Session::get('role') === 'foreman')
                <li class="menu-title"><span data-key="t-menu">Dashboard</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#DashboardProses" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="DashboardProses">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-dashboards">Dashboard Makro</span>
                    </a>
                    <div class="collapse menu-dropdown" id="DashboardProses">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{url('foreman/ggaggas/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis GGA & GGAS</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('foreman/blending/awal/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Blending Awal</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('foreman/blending/adjust/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Blending After Adjust</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('foreman/monitoring/blending/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Monitoring Turun Blending</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('foreman/monitoring/storage/dashboard')}}" class="nav-link" data-key="t-analytics">Analisis Monitoring Storage</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#Dashboardmikro" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="Dashboardmikro">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-dashboards">Dashboard Mikro</span>
                    </a>
                    <div class="collapse menu-dropdown" id="Dashboardmikro">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics">Analisis Blending After Adjust</a>
                            </li>
                            <li class="nav-item">
                                <a href="" class="nav-link" data-key="t-analytics">Analisis Monitoring Storage</a>
                            </li>

                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('foreman/rmpm/dashboard')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Dashboard RMPM</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('foreman/rmpm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/productionbatch/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('foreman/ggaggas/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">GGA & GGAS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('foreman/blending/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Blending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('foreman/monitoring/blending/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Monitoring Turun Blending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Scan</span>
                    </a>
                </li>
                @elseif(Session::get('role') === 'produksi')
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/productionbatch/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                    </a>
                </li>
                @elseif(Session::get('role') === 'analis' && Session::get('role_group') === 'rmpm')
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('analis/rmpm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('analis/rmpm/list/rm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Data RM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Scan</span>
                    </a>
                </li>

                @elseif(Session::get('role') === 'analis' && Session::get('role_group') === 'mikro' || Session::get('role_group') === 'makro')
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/ggaggas/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">GGA & GGAS</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/blending/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Blending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('analis/monitoring/blending/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Monitoring Turun Blending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Scan</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>


    <div class="sidebar-background"></div>
</div>