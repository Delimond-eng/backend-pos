@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppSales" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Ventes</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ventes</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Historique des produits retournés
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="date" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="me-3 mb-3 mb-sm-0">
                            <button class="btn btn-outline-primary btn-sm"> <i class="ri-file-pdf-fill me-1"></i> Exporter en PDF</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>
                    <div class="table-responsive" v-else>
                        <div class="table-responsive" v-if="filteredSales.length">
                            <table class="table text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-primary-transparent text-primary">Date création</th>
                                        <th class="bg-primary-subtle text-primary">Produit</th>
                                        <th class="bg-primary-transparent text-primary">Quantité</th>
                                        <th class="bg-primary-subtle text-primary">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data, index) in filteredReturns" :key="data.id">
                                        <td>
                                            <div class="fs-14 d-flex">
                                                <i class="ri-calendar-line me-2"></i>
                                                <span>@{{ data.created_at }}</span>
                                            </div>
                                        </td>
                                        <td class="fw-bold">
                                            @{{ data.product.name}}
                                        </td>
                                        <td class="fw-bold">
                                            @{{ data.quantity }}
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">Retourné</span>
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

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/sale.js') }}"></script>
@endsection
