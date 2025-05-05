@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppStock" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Rapport global de stock</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Rapport</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Rapport global de stock
                    </div>
                    <div class="d-flex">
                        <div class="me-2 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="dropdown"> <a href="javascript:void(0);" class="btn btn-info btn-sm btn-wave waves-effect waves-light" data-bs-toggle="dropdown" aria-expanded="false"> Exporter<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i> </a>
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
                                    <th scope="col" class="bg-primary-transparent text-primary">Produit</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Approvisionnement</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Ventes</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Retours</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Ajustements</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Autres sorties</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Stock actuel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allGlobalStocks" :key="index">
                                    <td class="fw-semibold">
                                        @{{ data.name}}
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.total_purchase }} </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.total_sale}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.total_return}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.total_adjustment}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.total_output}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.stock }}</span>
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
<script type="module" src="{{ asset('assets/js/scripts/stock.js') }}"></script>
@endsection