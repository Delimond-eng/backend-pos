@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppProduct" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Approvisionnement stock</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Approvisionnement</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Approvisionnement
                    </div>
                    <div class="card-subtitle text-info">Veuillez rechercher le produit dont le stock doit être approvisionnement !</div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
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
                                    <th scope="col" class="bg-primary-transparent text-primary">Libellé</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Catégorie</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Prix unitaire</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Stock</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">status</th>
                                    <th scope="col" class="bg-primary-subtle text-primary"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in filteredProducts" :key="index">
                                    <td :class="{'bg-danger-transparent text-danger':data.stock <= 0}">
                                        @{{ data.name  }}
                                    </td>
                                    <td :class="{'bg-danger-transparent text-danger':data.stock <= 0}">
                                        <span class="fw-semibold">@{{ data.category.name}} </span>
                                    </td>
                                    <td :class="{'bg-danger-transparent text-danger':data.stock <= 0}">
                                        <span class="fw-semibold">@{{ data.unit_price}}F</span>
                                    </td>
                                    <td :class="{'bg-danger-transparent text-danger':data.stock <= 0}">
                                        <span class="fw-semibold">@{{ data.stock }}</span>
                                    </td>
                                    <td :class="{'bg-danger-transparent text-danger':data.stock <= 0}">
                                        <span class="badge" :class="{'bg-success-transparent':data.stock > 0, 'bg-danger':data.stock === 0 }">@{{data.stock > 0 ? 'En stock' : 'Sans stock' }}</span>
                                    </td>
                                    <td :class="{'bg-danger-transparent text-danger':data.stock <= 0}">
                                        <div class="btn-list">
                                            <button title="approvionnement" @click="selectedProduct = data; appro.pu = selectedProduct.unit_price" data-bs-toggle="modal" data-bs-target="#stock-modal" class="btn btn-sm btn-primary btn-icon"><i class="ri-add-line"></i></button>
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
        <form class="modal-dialog modal-lg" @submit.prevent="addStock">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Approvisionnement stock | Produit : <span class="text-primary fw-bold" v-if="selectedProduct">@{{ selectedProduct.name }}</span></h6>
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
                        <div class="col-xl-4"> <label class="form-label">Date(optionnel)</label> <input type="date" v-model="appro.date" class="form-control border-primary-subtle"> </div>
                        <div class="col-xl-8"> <label class="form-label">Fournisseur(optionnel)</label> <input type="text" v-model="appro.supplier" class="form-control border-primary-subtle" placeholder="Fournisseur(optionnel)..."> </div>
                        <div class="col-xl-4" v-if="selectedProduct"> <label class="form-label">Stock existant</label> <input type="number" class="form-control text-primary border-primary-subtle fw-bold" :value="selectedProduct.stock" placeholder="0" readOnly> </div>
                        <div class="col-xl-8" v-if="selectedProduct"> <label class="form-label">Nouveau prix de vente(Optionnel)</label> <input type="number" v-model="appro.pu" class="form-control border-primary-subtle text-primary fw-bold" placeholder="0.00F"> </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-primary"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Approvisionner </button>
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