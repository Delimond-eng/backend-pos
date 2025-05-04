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
                        Liste des ventes
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="date" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="me-3 mb-3 mb-sm-0"><button class="btn btn-outline-primary btn-sm"><i class="ri-file-pdf-fill me-1"></i> Exporter en PDF</button></div>
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
                                        <th class="bg-primary-subtle text-primary">Client</th>
                                        <th class="bg-primary-transparent text-primary">Montant Total</th>
                                        <th class="bg-primary-subtle text-primary">Créer par</th>
                                        <th class="bg-primary-transparent text-primary">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(data, index) in filteredSales" :key="data.id">
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
                                                    <button title="Supprimer" class="btn btn-sm btn-danger btn-icon">
                                                        <span v-if="load_id == data.id"
                                                            class="spinner-border spinner-border-sm"
                                                            style="height:12px; width:12px"></span>
                                                        <i v-else class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Ligne de détails collapsible -->
                                        <tr :id="'details-' + data.id" class="collapse bg-light animated-collapse">
                                            <td colspan="5">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="bg-primary-transparent text-primary">Produit</th>
                                                            <th class="bg-primary-transparent text-primary">Quantité</th>
                                                            <th class="bg-primary-transparent text-primary">Prix unitaire</th>
                                                            <th class="bg-primary-transparent text-primary">Total</th>
                                                            <th class="bg-primary-transparent text-primary"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="item in data.items" :key="item.id">
                                                            <td>@{{ item.product.name }}</td>
                                                            <td>@{{ item.quantity }}</td>
                                                            <td>@{{ item.unit_price }}F</td>
                                                            <td>@{{ item.quantity * item.unit_price }}F</td>
                                                            <td>
                                                                <button title="Supprimer" class="btn btn-sm btn-danger-transparent btn-icon me-1">
                                                                    <!-- <span v-if="load_id == data.id"
                                                                        class="spinner-border spinner-border-sm"
                                                                        style="height:12px; width:12px"></span> -->
                                                                    <i class="ri-delete-bin-6-line"></i>
                                                                </button>
                                                                <button class="btn btn-outline-primary btn-sm" @click.prevent="selectedSaleItem=item; form.quantity = item.quantity" data-bs-target="#modal-returns" data-bs-toggle="modal"> <i class="ri-restart-line"></i> Retour produit</button>
                                                            </td>
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


    <div class="modal fade" id="modal-returns" aria-modal="true" role="dialog">
        <form class="modal-dialog" @submit.prevent="validReturn">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Retour | Produit : <span class="text-primary fw-bold" v-if="selectedSaleItem">@{{ selectedSaleItem.product.name }}</span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-3">
                        <div class="col-xl-12"> <label class="form-label">Quantité à retourner</label> <input type="number" v-model="form.quantity" class="form-control border-primary-subtle" placeholder="0"> </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-primary"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Valider le retour</button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/sale.js') }}"></script>
@endsection

@section("styles")
<style>
    .animated-collapse {
        transition: all 0.35s ease;
        overflow: hidden;
    }
</style>
@endsection
