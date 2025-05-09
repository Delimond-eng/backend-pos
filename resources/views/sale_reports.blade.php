@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppDashboard" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Rapport des ventes</h1>
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
                        Rapport de toutes les ventes
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <div class="input-group">
                                <input v-model="byDate" @input="getSaleReports" class="form-control form-control-sm border-primary-subtle" type="date" placeholder="Recherche produit " aria-label=".form-control-sm example">
                                <button class="btn btn-icon btn-primary btn-sm" @click="byDate=''; getSaleReports()"><i class="ri-restart-line"></i></button>
                            </div>
                        </div>
                        <div class="me-3 mb-3 mb-sm-0"><button class="btn btn-outline-primary btn-sm" @click="downloadSalePdf"><i class="ri-file-pdf-fill me-1"></i> Exporter en PDF</button></div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>
                    <div class="table-responsive" v-else>
                        <div class="table-responsive" v-if="filteredSaleReports.length">
                            <table class="table text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-primary-transparent text-primary">Date création</th>
                                        <th class="bg-primary-subtle text-primary">Client</th>
                                        <th class="bg-primary-transparent text-primary">Montant Total</th>
                                        <th class="bg-primary-subtle text-primary">Créer par</th>
                                        <th class="bg-primary-transparent text-primary">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(data, index) in filteredSaleReports" :key="data.id">
                                        <!-- Ligne principale -->
                                        <tr>
                                            <td>
                                                <div class="fs-14 d-flex">
                                                    <i class="ri-calendar-2-line me-2"></i>
                                                    <span>@{{ data.date }}</span>
                                                </div>
                                            </td>
                                            <td class="fw-bold">
                                                @{{ data.customer_name =='' ? "---" : data.customer_name}}
                                            </td>
                                            <td class="fw-bold">
                                                @{{ data.total_amount }}F
                                            </td>
                                            <td class="fw-bold">
                                                @{{ data.user.name }}
                                            </td>
                                            <td>
                                                <div class="btn-list">
                                                    <button title="Voir détails"
                                                        class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="collapse"
                                                        :data-bs-target="'#details-' + data.id"
                                                        :aria-controls="'details-' + data.id">
                                                        <i class="ri-eye-2-line me-1"></i>Voir détails
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Ligne de détails collapsible -->
                                        <tr :id="`details-${data.id}`"  class="collapse bg-light animated-collapse">
                                            <td colspan="5">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="bg-primary-transparent text-primary">Produit</th>
                                                            <th class="bg-primary-transparent text-primary">Quantité</th>
                                                            <th class="bg-primary-transparent text-primary">Prix unitaire</th>
                                                            <th class="bg-primary-transparent text-primary">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="item in data.items" :key="item.id">
                                                            <td>@{{ item.product.name }}</td>
                                                            <td>@{{ item.quantity }}</td>
                                                            <td>@{{ item.unit_price }}F</td>
                                                            <td>@{{ item.quantity * item.unit_price }}F</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </template>
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
<script type="module" src="{{ asset('assets/js/scripts/dashboard.js') }}"></script>
@endsection

@section("styles")
<style>
    .animated-collapse {
        transition: all 0.35s ease;
        overflow: hidden;
    }
</style>
@endsection
