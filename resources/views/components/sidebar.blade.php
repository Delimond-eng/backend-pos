<aside class="app-sidebar d-print-none sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="/" class="header-logo">
            <h1 class="desktop-dark text-danger text-uppercase fs-5 fw-bold">Zando <span class="text-white">Print</span></h1>
            <img src="{{ asset('assets/images/logos/logo.png') }}" alt="logo" class="toggle-dark">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">
        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu">
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Menu principal</span></li>
                <!-- End::slide__category -->

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{url('/')}}" class="side-menu__item {{ Route::is('home') ? 'active' : '' }}">
                        <i class="bx bx-home side-menu__icon"></i>
                        <span class="side-menu__label">Tableau de bord</span>
                    </a>
                </li>
                <!-- End::slide -->

                <li class="slide">
                    <a href="{{ route('clients') }}" class="side-menu__item {{ Route::is('clients') ? 'active' : '' }}">
                        <i class="bx bx-group side-menu__icon"></i>
                        <span class="side-menu__label">Clients</span>
                    </a>
                </li>


                <li class="slide">
                    <a href="{{ route('factures') }}" class="side-menu__item {{ Route::is('factures') ? 'active' : '' }}">
                        <i class="bx bx-bookmarks side-menu__icon"></i>
                        <span class="side-menu__label">Factures</span>
                    </a>
                </li>
                <!-- End::slide -->
                @if(Auth::user()->role === 'admin')
                <li class="slide">
                    <a href="{{ route('stockage') }}" class="side-menu__item  {{ Route::is('stockage') ? 'active' : '' }}">
                        <i class="bx bx-box side-menu__icon"></i>
                        <span class="side-menu__label">Stockage</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->role === 'admin' || Auth::user()->role==='gestionnaire stock')
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Manager</span></li>
                <!-- End::slide__category -->


                <li class="slide">
                    <a href="{{ route('paiements') }}" class="side-menu__item {{ Route::is('paiements') ? 'active' : '' }} ">
                        <i class="bx bx-money-withdraw side-menu__icon"></i>
                        <span class="side-menu__label">Paiements</span>
                    </a>
                </li>


                <li class="slide">
                    <a href="{{ route('accounting') }}" class="side-menu__item  {{ Route::is('accounting') ? 'active' : '' }}">
                        <i class="bx bx-archive-in side-menu__icon"></i>
                        <span class="side-menu__label">Compte de trésorerie</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="{{ route('inventories') }}" class="side-menu__item {{ Route::is('inventories') ? 'active' : '' }}">
                        <i class="bx bx-pie-chart side-menu__icon"></i>
                        <span class="side-menu__label">Inventaires</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('configuration') }}" class="side-menu__item  {{ Route::is('configuration') ? 'active' : '' }}">
                        <i class="bx bx-cog side-menu__icon"></i>
                        <span class="side-menu__label">Configuration</span>
                    </a>
                </li>



                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{route('users')}}" class="side-menu__item {{  Route::is('users') ? 'active' : '' }}">
                        <i class="bx bxs-user-account side-menu__icon"></i>
                        <span class="side-menu__label">Gestion utilisateurs</span>
                    </a>
                </li>
                @endif
                <!-- End::slide -->


            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
