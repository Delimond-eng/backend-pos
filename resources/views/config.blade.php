@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppConfig">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Configuration</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Configuration</li>
                </ol>
            </nav>
        </div>
    </div>



    <!-- Start::row-3 -->
    <div class="row">
        <div class="col-xxl-6 col-xl-12 col-md-12">
            <div class="card custom-card">
                <div class="card-header d-sm-flex d-block">
                    <div class="card-title">Liste des items de la facturation</div>
                    <div class="tab-menu-heading border-0 p-0 ms-auto mt-sm-0 mt-2">

                    </div>
                    <div class="d-sm-flex mt-sm-0 mt-2">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input class="form-control form-control-sm" v-model="search" type="text" placeholder="Recherche client par nom" aria-label=".form-control-sm example">
                        </div>

                        <button type="button" class="btn btn-sm btn-primary align-items-center d-inline-flex" data-bs-toggle="modal" data-bs-target="#config-modal"><i class="ti ti-plus me-1 fw-semibold"></i>Ajout item</button>

                    </div>

                </div>
                <div class="card-body p-0">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>

                    <div v-else class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Produit Item libellé</th>
                                    <th scope="col">Nombre des natures</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allItems" :key="index">
                                    <td>@{{ data.item_create_At }}</td>
                                    <td>@{{ data.item_libelle }}</td>
                                    <td><span class="badge bg-secondary">@{{ data.natures.length }} natures</span></td>
                                    <td class="text-muted">
                                        <div class="btn-list">
                                            <button @click.prevent="showEditItem(data)" title="voir détails & ajout natures" class="btn btn-sm btn-primary-light btn-icon"><i class="ri-add-line"></i></button>
                                            <button title="Supprimer item" @click.prevent="deleteItem(data)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
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
    <!-- End::row-3 -->

    <div class="modal fade" id="config-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="submitForm" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Création item & natures</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Libelle item</label>
                            <input type="text" v-model="form.item_libelle" class="form-control" id="name" placeholder="Désignation..." required>
                        </div>
                    </div>

                </div>
                <div class="modal-body px-4 py-2">
                    <div class="d-flex justify-content-between align-items-start w100 mb-2">
                        <h6>Natures</h6>
                        <button type="button" @click.prevent="addNatureField" class="btn btn-sm btn-primary-light align-items-center d-inline-flex"><i class="ti ti-plus"></i>Ajouter nature</button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-lg-12 col-12" v-for="(nature, index) in form.natures" :key="index">
                            <div class="d-flex">
                                <input type="text" v-model="nature.item_nature_libelle" class="form-control form-control-sm me-2" placeholder="Entrez le libellé de la nature de l'item" required />
                                <div class="d-flex">
                                    <input type="text" v-model="nature.item_nature_prix" class="form-control form-control-sm me-1 flex-fill" id="name" placeholder="prix unitaire" required>
                                    <select class="form-select form-select-sm" v-model="nature.item_nature_prix_devise" style="width:100px">
                                        <option value="CDF" selected>CDF</option>
                                        <option value="USD">USD</option>
                                    </select>
                                </div>
                                <button v-show="form.natures.length > 1" @click.prevent="deleteNatureField(index)" class="btn btn-sm btn-icon btn-danger-light  ms-2"> <i class="ri-close-line"></i> </button>

                            </div>
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
<script type="module" src="{{ asset('assets/js/scripts/config.js') }}"></script>
@endsection
