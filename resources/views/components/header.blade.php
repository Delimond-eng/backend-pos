<header class="app-header d-print-none">
    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="/" class="header-logo">
                        <!--<img src="assets/images/brand-logos/logo-02.png" alt="logo" class="desktop-logo">
                        <img src="assets/images/brand-logos/logo-02.png" alt="logo" class="toggle-logo">
                        <img src="assets/images/brand-logos/logo-02.png" alt="logo" class="desktop-dark">
                        <img src="assets/images/brand-logos/logo-02.png" alt="logo" class="toggle-dark">
                        <img src="assets/images/brand-logos/logo-02.png" alt="logo" class="desktop-white">
                        <img src="assets/images/brand-logos/logo-02.png" alt="logo" class="toggle-white"> -->
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">
            
            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="d-flex align-items-center">
                        <div class="me-sm-2 me-0">
                            <img src="{{asset('assets/images/faces/9.jpg')}}" alt="img" width="32" height="32" class="rounded-circle">
                        </div>
                        <div class="d-sm-block d-none">
                            <p class="fw-semibold mb-0 lh-1">{{ Auth::check() ?  Auth::user()->name : '' }}</p>
                            <span class="op-7 fw-normal d-block fs-11">{{Auth::check() ? Auth::user()->role : '' }}</span>
                        </div>
                    </div>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <ul class="main-header-dropdown dropdown-menu pt-0 overflow-hidden header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                    <li><a class="dropdown-item d-flex" href="#"><i class="ti ti-user-circle fs-18 me-2 op-7 text-info"></i>Profile</a></li>
                    <li><a class="dropdown-item d-flex" href="{{ route("sale.portal") }}"><i class="ti ti-shopping-cart fs-18 me-2 op-7 text-primary"></i>Portail de vente</a></li>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="d-none">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                    <li><a class="dropdown-item d-flex" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ti ti-logout fs-18 me-2 op-7 text-danger"></i>Deconnexion</a></li>
                </ul>
            </div>
            <!-- End::header-element -->
        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
