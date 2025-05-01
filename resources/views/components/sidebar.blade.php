<aside class="app-sidebar d-print-none sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="/" class="header-logo">
            <h1 class="desktop-dark text-danger text-uppercase fs-5 fw-bold">Rapid <span class="text-white">Tech</span></h1>
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

                <li class="slide has-sub"> 
                    <a href="javascript:void(0);" class="side-menu__item {{ Route::is('home') ? 'active' : '' }}"> 
                        <i class="bx bx-home side-menu__icon"></i> 
                        <span class="side-menu__label">Tableaux de bord</span> <i class="fe fe-chevron-right side-menu__angle"></i> 
                    </a>
                    <ul class="slide-menu child1" style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate3d(119.2px, 122.4px, 0px); display: none; box-sizing: border-box;" data-popper-placement="bottom">
                        <!-- <li class="slide side-menu__label1"> <a href="javascript:void(0)">Error</a> </li> -->
                        <li class="slide"> <a href="{{url('/')}}" class="side-menu__item">Vue d'ensemble</a> </li>
                        <li class="slide"> <a href="#" class="side-menu__item">Alertes de stock</a> </li>
                        <li class="slide"> <a href="#" class="side-menu__item">Rapport des ventes</a> </li>
                        <li class="slide"> <a href="#" class="side-menu__item">Rapport des achats</a> </li>
                        <li class="slide"> <a href="#" class="side-menu__item">Rapport de stock</a> </li>
                        <li class="slide"> <a href="#" class="side-menu__item">Rapport de profits</a> </li>
                        <li class="slide"> <a href="#" class="side-menu__item">Rapport des dépenses</a> </li>
                    </ul>
                </li>
                <!-- End::slide -->

                <li class="slide has-sub"> 
                    <a href="javascript:void(0);" class="side-menu__item"> <i class="bx bx-bookmarks side-menu__icon"></i> 
                        <span class="side-menu__label">Produits</span> <i class="fe fe-chevron-right side-menu__angle"></i> 
                    </a>
                    <ul class="slide-menu child1" style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate3d(119.2px, 122.4px, 0px); display: none; box-sizing: border-box;" data-popper-placement="bottom">
                        <li class="slide side-menu__label1"> <a href="javascript:void(0)">Liste des produits</a> </li>
                        <li class="slide"> <a href="401-error.html" class="side-menu__item">Création produit</a> </li>
                        <li class="slide"> <a href="{{ route("view.categories") }}" class="side-menu__item">Catégories</a> </li>
                        <li class="slide"> <a href="500-error.html" class="side-menu__item">Inventaires</a> </li>
                    </ul>
                </li>

                <li class="slide has-sub"> 
                    <a href="javascript:void(0);" class="side-menu__item"> <i class="bx bx-archive-in side-menu__icon"></i> 
                        <span class="side-menu__label">Approvisionnement</span> <i class="fe fe-chevron-right side-menu__angle"></i> 
                    </a>
                    <ul class="slide-menu child1" style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate3d(119.2px, 122.4px, 0px); display: none; box-sizing: border-box;" data-popper-placement="bottom">
                        <li class="slide"> <a href="" class="side-menu__item">Nouveau stock</a> </li>
                        <li class="slide"> <a href="" class="side-menu__item">Historique</a> </li>
                    </ul>
                </li>

                <li class="slide has-sub"> 
                    <a href="javascript:void(0);" class="side-menu__item"> <i class="bx bx-money-withdraw side-menu__icon"></i> 
                        <span class="side-menu__label">Ventes</span> <i class="fe fe-chevron-right side-menu__angle"></i> 
                    </a>
                    <ul class="slide-menu child1" style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate3d(119.2px, 122.4px, 0px); display: none; box-sizing: border-box;" data-popper-placement="bottom">
                        <li class="slide"> <a href="" class="side-menu__item">Liste des ventes</a> </li>
                        <li class="slide"> <a href="" class="side-menu__item">Retours de vente</a> </li>
                    </ul>
                </li>

                <li class="slide has-sub"> 
                    <a href="javascript:void(0);" class="side-menu__item"> <i class="bx bx-chart side-menu__icon"></i> 
                        <span class="side-menu__label">Depenses</span> <i class="fe fe-chevron-right side-menu__angle"></i> 
                    </a>
                    <ul class="slide-menu child1" style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate3d(119.2px, 122.4px, 0px); display: none; box-sizing: border-box;" data-popper-placement="bottom">
                        <li class="slide"> <a href="" class="side-menu__item">Liste des dépenses</a> </li>
                        <li class="slide"> <a href="" class="side-menu__item">Nouvelle dépense</a> </li>
                        <li class="slide"> <a href="" class="side-menu__item">Types de dépenses</a> </li>
                        <li class="slide"> <a href="" class="side-menu__item">Statistiques</a> </li>
                    </ul>
                </li>
                <li class="slide has-sub"> 
                    <a href="javascript:void(0);" class="side-menu__item"> <i class="bx bx-archive-out side-menu__icon"></i> 
                        <span class="side-menu__label">Mouvements de Stock</span> <i class="fe fe-chevron-right side-menu__angle"></i> 
                    </a>
                    <ul class="slide-menu child1" style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate3d(119.2px, 122.4px, 0px); display: none; box-sizing: border-box;" data-popper-placement="bottom">
                        <li class="slide"> <a href="" class="side-menu__item">Ajustement de stock</a> </li>
                        <li class="slide"> <a href="" class="side-menu__item">Historique de stock</a> </li>
                    </ul>
                </li>
                <!-- Start::slide__category -->
                <li class="slide__category"><span class="category-name">Manager</span></li>
                <!-- End::slide__category -->
                <li class="slide">
                    <a href="" class="side-menu__item">
                        <i class="bx bx-cog side-menu__icon"></i>
                        <span class="side-menu__label">Configuration</span>
                    </a>
                </li>

                <!-- Start::slide -->
                <li class="slide">
                    <a href="{{route('users')}}" class="side-menu__item {{  Route::is('users') ? 'active' : '' }}">
                        <i class="bx bxs-user-circle side-menu__icon"></i>
                        <span class="side-menu__label">Gestion utilisateurs</span>
                    </a>
                </li>
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