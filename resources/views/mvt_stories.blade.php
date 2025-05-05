@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppProduct" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0"> {{ Route::is("purchase.reports") ? 'Rapport des achats' : "Historique d'approvisionnement" }} </h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Historique</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                    {{ Route::is("purchase.reports") ? 'Liste des achats' : "Mouvements achat" }}
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="mb-3 mb-sm-0">
                            <input class="form-control form-control-sm border-primary-subtle" type="date" required />
                        </div>
                        <span class="text-capitalize mx-1">--</span>
                        <div class="me-2 mb-3 mb-sm-0">
                            <input class="form-control form-control-sm border-primary-subtle" type="date" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="ri-filter-line"></i>
                            Filtrer
                        </button>
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
                                    <th scope="col" class="bg-primary-subtle text-primary">Date création</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Date approvisionnement</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Produit</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Quantité</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Prix achat Unitaire</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Total</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Fournisseur</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Fait par</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">
                                        <button title="actualiser" onclick="location.reload();" class="btn btn-sm btn-outline-secondary btn-icon"><i class="ri-refresh-line"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in filteredPurchases" :key="index">
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.created_at }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @{{ data.purchase.date }}
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{data.product.name}} </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.quantity}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.unit_price }}F</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.purchase.total_amount }}F</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.purchase.supplier_name ?? '---' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.purchase.user.name }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            @if(Auth::user()->role=="admin")
                                            <button title="Editer" @click.prevent="editAppro(data)" data-bs-toggle="modal" data-bs-target="#stock-modal" class="btn btn-sm btn-info btn-icon me-1"><i class="ri-edit-2-line"></i></button>
                                            <button title="Delete" @click.prevent="deleteApprov(data.product.id , data.quantity, data.id)" class="btn btn-sm btn-danger btn-icon contact-delete">
                                                <span v-if="load_id == data.id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="stock-modal" aria-modal="true" role="dialog">
        <form class="modal-dialog modal-lg" @submit.prevent="updateStock">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Approvisionnement stock | Produit : <span class="text-primary fw-bold" v-if="selectedAppro">@{{ selectedAppro.product.name }}</span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div v-if="result" class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Succès !</strong>@{{ result }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-3">
                        <div class="col-xl-6"> <label class="form-label">Prix d'achat unitaire</label> <input type="number" v-model="appro.pa" class="form-control border-primary-subtle" placeholder="0.00F"> </div>
                        <div class="col-xl-6"> <label class="form-label">Quantité</label> <input type="number" v-model="appro.qty" class="form-control border-primary-subtle" placeholder="0"> </div>
                        <div class="col-xl-6"> <label class="form-label">Date(optionnel)</label> <input type="date" v-model="appro.date" class="form-control border-primary-subtle"> </div>
                        <div class="col-xl-6"> <label class="form-label">Fournisseur(optionnel)</label> <input type="text" v-model="appro.supplier" class="form-control border-primary-subtle" placeholder="Fournisseur(optionnel)..."> </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-primary"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Valider les modifications </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/product.js') }}"></script>
@endsection
