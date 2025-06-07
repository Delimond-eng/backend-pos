@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppStock" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Rapport de stock</h1>
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
                        Rapport de stock
                    </div>
                    <div class="d-flex">
                        <div class="me-2 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="me-2 mb-sm-0">
                            <select v-model="type" class="form-control form-control-sm border-primary-subtle">
                                <option value="" selected hidden>Type</option>
                                <option value="">Tout</option>
                                <option value="purchase">Approvisionnement</option>
                                <option value="sale">Vente</option>
                                <option value="adjustment">Ajustement</option>
                                <option value="outpout">Autre sortie</option>
                            </select>
                        </div>
                        <div class="me-2 d-flex justify-content-center align-items-center">
                            <input v-model="date.start" class="form-control form-control-sm border-primary-subtle" type="date" />
                            <span>--</span>
                            <input v-model="date.end" class="form-control form-control-sm border-primary-subtle" type="date" />
                            <button @click="getReports" class="btn btn-primary btn-sm btn-icon ms-2">
                                <i class="ri-search-2-line"></i>
                            </button>
                            <button onclick="location.reload()" class="btn btn-primary-light btn-sm btn-icon ms-2">
                                <i class="ri-refresh-line"></i>
                            </button>
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
                                    <th scope="col" class="bg-primary-subtle text-primary">Date</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Produit</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Type mouvement</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allReports" :key="index">
                                    <td :class="{'text-success':data.type == 'purchase', 'text-primary':data.type == 'adjustment', 'text-info':data.type == 'sale', 'text-danger':data.type == 'outpout'}">
                                        @{{ data.created_at  }}
                                    </td>
                                    <td :class="{' text-success':data.type == 'purchase', 'text-primary':data.type == 'adjustment', 'text-info':data.type == 'sale', 'text-danger':data.type == 'outpout'}">
                                        <span class="fw-semibold">@{{ data.product.name }} </span>
                                    </td>
                                    <td :class="{' text-success':data.type == 'purchase', 'text-primary':data.type == 'adjustment', 'text-info':data.type == 'sale', 'text-danger':data.type == 'outpout'}">
                                        <span class="fw-semibold">@{{ data.type}}</span>
                                    </td>
                                    <td :class="{' text-success':data.type == 'purchase', 'text-primary':data.type == 'adjustment', 'text-info':data.type == 'sale', 'text-danger':data.type == 'outpout'}">
                                        <span class="fw-semibold">@{{ data.quantity }}</span>
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