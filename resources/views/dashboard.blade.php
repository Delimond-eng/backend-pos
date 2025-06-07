@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Tableau de bord</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start::row-1 -->
    <div class="row" id="AppDashboard" v-cloak>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <p class="mb-2"> <span class="fs-16">Ventes journalières</span> </p>
                        <div class="col-6 pe-0">
                            <p class="mb-2 fs-12"> <span class="fs-25 fw-semibold lh-1 vertical-bottom mb-0" v-if="dashCounts">@{{ dashCounts.daily_sales }} F</span> <span class="d-block fs-10 fw-semibold text-muted">AUJOURD'HUI</span> </p>
                        </div>
                        <div class="col-6">
                            <p class="main-card-icon mb-0">
                                <i class="ri-shopping-cart-2-fill text-primary ri-3x"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <p class="mb-2"> <span class="fs-16">Ventes mensuelles</span> </p>
                        <div class="col-8 pe-0">
                            <p class="mb-2 fs-12"> <span class="fs-25 fw-semibold lh-1 vertical-bottom mb-0" v-if="dashCounts"> @{{ dashCounts.monthly_sales }}F</span> <span class="d-block fs-10 fw-semibold text-muted">CE MOIS</span> </p>
                        </div>
                        <div class="col-4">
                            <p class="main-card-icon mb-0">
                                <i class="ri-shopping-cart-fill text-success ri-3x"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <p class="mb-2"> <span class="fs-16">Dépenses mensuelles</span> </p>
                        <div class="col-8 pe-0">
                            <p class="mb-2 fs-12"> <span class="fs-25 fw-semibold lh-1 vertical-bottom mb-0" v-if="dashCounts"> @{{dashCounts.monthly_expenses}} F</span> <span class="d-block fs-10 fw-semibold text-muted">CE MOIS</span> </p>
                        </div>
                        <div class="col-4">
                            <p class="main-card-icon mb-0">
                                <i class="ri-money-dollar-box-fill text-warning ri-3x"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="row">
                        <p class="mb-2"> <span class="fs-16">Produits sans stock</span> </p>
                        <div class="col-8 pe-0">
                            <p class="mb-2 fs-12"> <span class="fs-25 fw-semibold lh-1 vertical-bottom mb-0" v-if="dashCounts">@{{ dashCounts.stock_null.toString().padStart(2, '0') }}</span> <span class="d-block fs-10 fw-semibold text-muted">CE MOIS</span> </p>
                        </div>
                        <div class="col-4">
                            <p class="main-card-icon mb-0">
                                <i class="ri-bar-chart-fill text-danger ri-3x"></i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card custom-card">
                <div class="card-body pb-0 px-2 pt-0">
                    <div class="row pt-0">
                        <div class="col-md-4 col-6 border-end p-3 text-center">
                            <p class="mb-1 fw-semibold text-primary text-capitalize">Total des produits</p>
                            <h5 class="mb-1 fw-semibold" v-if="dashCounts">@{{ dashCounts.products.toString().padStart(2, '0')  }}</h5>
                        </div>
                        <div class="col-md-4 col-6 border-end p-3 text-center">
                            <p class="mb-1 fw-semibold text-primary text-capitalize">Total des produits vendus</p>
                            <h5 class="mb-1 fw-semibold" v-if="dashCounts">@{{ dashCounts.sales }} F</h5>
                        </div>
                        <div class="col-md-4 col-6 p-3 text-center">
                            <p class="mb-1 fw-semibold text-primary text-capitalize">Revenu global</p>
                            <h5 class="mb-1 fw-semibold" v-if="dashCounts">@{{ dashCounts.revenue }} F</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Liste des produits & leur stock actuel
                    </div>
                    <div class="d-flex">
                        <div class="me-2">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="dropdown"> <a href="javascript:void(0);" class="btn btn-info btn-sm btn-wave waves-effect waves-light" data-bs-toggle="dropdown" aria-expanded="false"> Exporter en<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i> </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a class="dropdown-item" href="javascript:void(0);">Excel</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">PDF</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>
                    <div class="table-responsive" v-else>
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="bg-primary-transparent text-primary">Date création</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Libellé</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Catégorie</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Prix unitaire</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Stock</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in filteredProducts" :key="index">
                                    <td :class="{'text-danger':data.stock <= 0}">
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.created_at}}</span>
                                        </div>
                                    </td>
                                    <td class="fw-bold" :class="{'text-danger':data.stock <= 0}">
                                        @{{ data.name  }}
                                    </td>
                                    <td :class="{'text-danger':data.stock <= 0}">
                                        <span class="fw-semibold">@{{ data.category.name}} </span>
                                    </td>
                                    <td :class="{'text-danger':data.stock <= 0}">
                                        <span class="fw-semibold">@{{ data.unit_price}}F</span>

                                    </td>
                                    <td :class="{'text-danger':data.stock <= 0}">
                                        <span class="fw-semibold">@{{ data.stock }}</span>
                                    </td>
                                    <td :class="{'text-danger':data.stock <= 0}">
                                        <span class="badge" :class="{'bg-success-transparent':data.stock > 0, 'bg-danger-transparent':data.stock === 0 }">@{{data.stock > 0 ? 'En stock' : 'Sans stock' }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/dashboard.js') }}"></script>
@endsection