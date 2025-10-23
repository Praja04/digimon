<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="#" class="logo">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" alt="" height="25">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/icon-utility/kecap.png') }}" alt="" height="100">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                @if (Session::get('role') === 'dept_head')
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/rmpm') }}">
                            <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/productionbatch') }}">
                            <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                        </a>
                    </li>
                @elseif(Session::get('role') === 'supervisor')
                    <li class="menu-title"><span data-key="t-menu">Dashboard</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#Dashboards" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="Dashboards">
                            <i class="mdi mdi-monitor-dashboard"></i> <span data-key="t-dashboards">Dashboard</span>
                        </a>
                        <div class="collapse menu-dropdown" id="Dashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item"><a href="{{ url('dashboard/gga-ggas') }}" class="nav-link"><i
                                            class="mdi mdi-flask"></i> Analisis GGA & GGAS</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/blending/awal') }}" class="nav-link"><i
                                            class="mdi mdi-blender"></i> Analisis Blending Awal</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/blending/after') }}" class="nav-link"><i
                                            class="mdi mdi-blender-outline"></i> Analisis Blending After Adjust</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/monitoring/turun') }}"
                                        class="nav-link"><i class="mdi mdi-chart-line"></i> Monitoring Turun
                                        Blending</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/monitoring/storage') }}"
                                        class="nav-link"><i class="mdi mdi-database"></i> Monitoring Storage</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/mikro/blending/after') }}"
                                        class="nav-link"><i class="mdi mdi-blender"></i> Blending After Adjust</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/mikro/monitoring/storage') }}"
                                        class="nav-link"><i class="mdi mdi-database"></i> Monitoring Storage</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/rm') }}" class="nav-link"><i
                                            class="mdi mdi-chemical-weapon"></i> Dashboard RMPM</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                    <li class="nav-item"><a class="nav-link menu-link" href="{{ url('supervisor/rmpm/list/rm') }}"><i
                                class="mdi mdi-format-list-bulleted-type"></i> Data RM</a></li>

                    <li class="nav-item"><a class="nav-link menu-link"
                            href="{{ url('analis/productionbatch/menu') }}"><i class="mdi mdi-food-fork-drink"></i>
                            Persiapan Masak</a></li>

                    <li class="nav-item"><a class="nav-link menu-link" href="{{ url('supervisor/ggaggas/menu') }}"><i
                                class="mdi mdi-flask-outline"></i> GGA &
                            GGAS</a></li>

                    <li class="nav-item"><a class="nav-link menu-link" href="{{ url('supervisor/blending/menu') }}"><i
                                class="mdi mdi-blender-software"></i>
                            Blending</a></li>

                    {{-- <li class="nav-item"><a class="nav-link menu-link"
                            href="{{ url('supervisor/monitoring/blending/menu') }}"><i
                                class="mdi mdi-monitor-dashboard"></i> Monitoring Blending</a></li> --}}

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('supervisor/monitoring/pasteurisasi/menu') }}">
                            <i class="mdi mdi-thermometer"></i> <span>Monitoring Pasteurisasi & Storage</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('supervisor/monitoring/blending/menu') }}">
                            <i class="mdi mdi-bottle-wine"></i> <span>Monitoring Filling</span>
                        </a>
                    </li>

                    <li class="nav-item"><a class="nav-link menu-link"
                            href="{{ url('analis/productionbatch/scan') }}"><i class="mdi mdi-qrcode-scan"></i>
                            Scan</a></li>

                    <li class="menu-title"><span data-key="t-menu">Management</span></li>
                    <li class="nav-item"><a class="nav-link menu-link"
                            href="{{ url('supervisor/manajemen_user') }}"><i class="mdi mdi-account-cog"></i> Manage
                            User</a></li>
                @elseif(Session::get('role') === 'foreman')
                    <li class="menu-title"><span data-key="t-menu">Dashboard</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#Dashboards" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="Dashboards">
                            <i class="mdi mdi-monitor-dashboard"></i> <span>Dashboard</span>
                        </a>
                        <div class="collapse menu-dropdown" id="Dashboards">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item"><a href="{{ url('dashboard/gga-ggas') }}" class="nav-link"><i
                                            class="mdi mdi-flask-outline"></i> Analisis GGA & GGAS</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/blending/awal') }}"
                                        class="nav-link"><i class="mdi mdi-blender"></i> Analisis Blending Awal</a>
                                </li>
                                <li class="nav-item"><a href="{{ url('dashboard/blending/after') }}"
                                        class="nav-link"><i class="mdi mdi-blender-outline"></i> Blending After
                                        Adjust</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/monitoring/turun') }}"
                                        class="nav-link"><i class="mdi mdi-chart-line"></i> Monitoring Turun
                                        Blending</a></li>
                                <li class="nav-item"><a href="{{ url('dashboard/monitoring/storage') }}"
                                        class="nav-link"><i class="mdi mdi-database-check"></i> Monitoring Storage</a>
                                </li>
                                <li class="nav-item"><a href="{{ url('dashboard/mikro/blending/after') }}"
                                        class="nav-link"><i class="mdi mdi-blender"></i> Mikro Blending After
                                        Adjust</a>
                                </li>
                                <li class="nav-item"><a href="{{ url('dashboard/mikro/monitoring/storage') }}"
                                        class="nav-link"><i class="mdi mdi-database"></i> Mikro Monitoring Storage</a>
                                </li>
                                <li class="nav-item"><a href="{{ url('dashboard/rm') }}" class="nav-link"><i
                                            class="mdi mdi-chemical-weapon"></i> Dashboard RMPM</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#ManageData" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="ManageData">
                            <i class="mdi mdi-database-cog"></i> <span>Manage Data</span>
                        </a>
                        <div class="collapse menu-dropdown" id="ManageData">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ url('data') }}" class="nav-link">
                                        <i class="mdi mdi-palette-swatch"></i> Manage Warna
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="menu-title"><span>Menu</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('foreman/rmpm/list/rm') }}">
                            <i class="mdi mdi-database-search"></i> <span>Data RM</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/productionbatch/menu') }}">
                            <i class="mdi mdi-food-fork-drink"></i> <span>Persiapan Masak</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('foreman/ggaggas/menu') }}">
                            <i class="mdi mdi-flask"></i> <span>GGA & GGAS</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('foreman/blending/menu') }}">
                            <i class="mdi mdi-blender-software"></i> <span>Blending</span>
                        </a>
                    </li>

                    {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('foreman/monitoring/blending/menu') }}">
                        <i class="mdi mdi-monitor-dashboard"></i> <span>Monitoring Blending</span>
                    </a>
                </li> --}}

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('foreman/monitoring/pasteurisasi/menu') }}">
                            <i class="mdi mdi-thermometer"></i> <span>Monitoring Pasteurisasi & Storage</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('foreman/monitoring/blending/menu') }}">
                            <i class="mdi mdi-bottle-wine"></i> <span>Monitoring Filling</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan') }}">
                            <i class="mdi mdi-qrcode-scan"></i> <span>Scan</span>
                        </a>
                    </li>
                @elseif(Session::get('role') === 'produksi')
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/productionbatch/menu') }}">
                            <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                        </a>
                    </li>
                @elseif(Session::get('role') === 'analis' && Session::get('role_group') === 'rmpm')
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/rmpm/list/rm') }}">
                            <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Data RM</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan') }}">
                            <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Scan</span>
                        </a>
                    </li>
                @elseif(Session::get('role') === 'analis' && Session::get('role_group') === 'field')
                    <li class="nav-item"><a class="nav-link menu-link"
                            href="{{ url('supervisor/blending/menu') }}"><i class="mdi mdi-blender-software"></i>
                            Blending</a></li>
                @elseif(
                    (Session::get('role') === 'analis' && Session::get('role_group') === 'mikro') ||
                        Session::get('role_group') === 'makro')
                    <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/ggaggas/menu') }}">
                            <i class="mdi mdi-flask-outline"></i> <span data-key="t-widgets">GGA & GGAS</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/blending/menu') }}">
                            <i class="mdi mdi-blender"></i> <span data-key="t-widgets">Blending</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/monitoring/pasteurisasi/menu') }}">
                            <i class="mdi mdi-thermometer"></i> <span>Monitoring Pasteurisasi & Storage</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/monitoring/blending/menu') }}">
                            <i class="mdi mdi-bottle-wine"></i> <span>Monitoring Filling</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ url('analis/productionbatch/scan') }}">
                            <i class="mdi mdi-qrcode-scan"></i> <span data-key="t-widgets">Scan</span>
                        </a>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>


    <div class="sidebar-background"></div>
</div>
