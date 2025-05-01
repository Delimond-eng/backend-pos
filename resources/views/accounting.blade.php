@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppAccount" v-cloak>
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Gestion compte de trésorerie</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Compte trésorerie</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        liste des utilisateurs
                    </div>
                    <div class="d-sm-flex">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#account-modal"> <i class="ri-add-line"></i> Créez un compte </button>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>

                    <div v-else class="row g-2">
                        <div class="col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12" v-for="(data, index) in accounts" :key="index">
                            <div>
                                <div class="category-link success text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="category-svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24">
                                        <rect fill="none" height="24" width="24"></rect>
                                        <g opacity=".3">
                                            <path d="M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"></path>
                                        </g>
                                        <g>
                                            <path d="M16,11V3H8v6H2v12h20V11H16z M10,5h4v14h-4V5z M4,11h4v8H4V11z M20,19h-4v-6h4V19z"></path>
                                        </g>
                                    </svg>
                                    <p class="fs-14 mb-1 text-default fw-semibold">@{{ data.compte_libelle }}</p>
                                    <div class="btn-list">
                                        <button title="Voir transactions" class="btn btn-sm btn-primary-light btn-icon"><i class="bx bx-chart"></i></button>
                                        <button title="Supprimer" @click.prevent="deleteAccount(data.id)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
                                            <span v-if="load_id == data.id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>



    <div class="modal fade" id="account-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="submitForm" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Création utilisateur</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Libelle Compte</label>
                            <input type="text" v-model="form.libelle" class="form-control" id="name" placeholder="Nom d'utilisateur" required>
                        </div>

                        <div class="col-xl-12">
                            <label for="role" class="form-label">Devise</label>
                            <select v-model="form.devise" class="form-select" id="role">
                                <option value="" selected hidden>Sélectionnez une devise</option>
                                <option value="CDF">CDF</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-success"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Créer </button>
                </div>
            </div>
        </form>
    </div>


</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/account.js') }}"></script>
@endsection
