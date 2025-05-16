<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Light Logo-->
        <a href="{{ url('rmpm') }}" class="logo logo-light">
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

                <!-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarDashboards">
                        <i class="mdi mdi-tools"></i> <span data-key="t-dashboards">Dashboard Eng</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ url('dept_head') }}" class="nav-link" data-key="t-analytics"> Analytics Boiler</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('dept_head') }}" class="nav-link" data-key="t-analytics"> Todo List ENG </a>
                            </li>

                        </ul>
                    </div>
                </li> -->

                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('rmpm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('productionbatch')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                    </a>
                </li>
                @elseif(Session::get('role') === 'supervisor')
                @elseif(Session::get('role') === 'foreman')
                @elseif(Session::get('role') === 'analis')
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ url('rmpm')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">RMPM</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('productionbatch/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">Persiapan Masak</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{url('ggaggas/menu')}}">
                        <i class="mdi mdi-puzzle-outline"></i> <span data-key="t-widgets">GGA & GGAS</span>
                    </a>
                </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>


    <div class="sidebar-background"></div>
</div>