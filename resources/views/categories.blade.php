@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppCategories" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Catégories de produits</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Catégories</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-5">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Liste des catégories
                    </div>
                    <div class="d-sm-flex">
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#categorie-modal"> <i class="ri-add-line"></i> Créez un nouveau utilisateur </button>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>

                    <ul v-else class="list-unstyled mb-0 personal-upcoming-events">
                        <li class="border-bottom border-1 pb-2" v-for="(data, index) in categories" :key="index">
                            <div class="d-flex align-items-center">
                                <div class="flex-fill">
                                    <span class="fw-bold"> @{{ data.name }}</span>
                                </div>
                                <div>
                                    <div class="btn-list">
                                        @if (Auth::user()->role=="admin")
                                        <button title="Supprimer" @click.prevent="deleteCat(data.id)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
                                            <span v-if="load_id == data.id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categorie-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="submitForm" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Création catégorie</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Libellé</label>
                            <input type="text" v-model="form.name" class="form-control" id="name" placeholder="Catégorie libellé" required>
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
<script type="module" src="{{ asset('assets/js/scripts/categorie.js') }}"></script>
@endsection
