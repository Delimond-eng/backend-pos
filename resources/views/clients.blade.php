@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppClient" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Gestion des clients</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Clients</li>
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
                        liste des clients
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input class="form-control form-control-sm" v-model="search" type="text" placeholder="Recherche client par nom" aria-label=".form-control-sm example">
                        </div>

                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#client-modal"> <i class="ri-add-line"></i> Créez un nouveau client </button>

                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>

                    <div v-else class="table-responsive">
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Date création</th>
                                    <th scope="col">Nom</th>
                                    <th scope="col">Téléphone</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in clientsData" :key="index">

                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{data.client_create_At }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-14">@{{ data.client_nom }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.client_tel }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button title="Voir détails" class="btn btn-sm btn-info-light btn-icon"><i class="ri-eye-line"></i></button>
                                            <button title="Supprimer" @click.prevent="deleteClient(data.id)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
                                                <span v-if="load_id == data.id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button>
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


    <div class="modal fade" id="client-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="submitForm" class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Création d'un client</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-6">
                            <label for="name" class="form-label">Nom du client</label>
                            <input type="text" v-model="form.name" class="form-control" id="name" placeholder="Nom complet" required>
                        </div>
                        <div class="col-xl-6">
                            <label for="task-id" class="form-label">Téléphone</label>
                            <input type="text" v-model="form.phone" class="form-control" id="phone" placeholder="Téléphone">
                        </div>
                        <div class="col-xl-12">
                            <label for="text-area" class="form-label">Adresse(optionnelle)</label>
                            <textarea class="form-control" v-model="form.address" id="adresse" rows="2" placeholder="Adresse"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-success"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Créer </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/client.js') }}"></script>
@endsection
