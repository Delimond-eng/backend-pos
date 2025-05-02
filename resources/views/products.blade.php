@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppProduct" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Produits</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Produits</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Liste des produits
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>

                        <a href="{{ route('view.products.add') }}" class="btn btn-primary btn-sm"> <i class="ri-add-line"></i> Ajouter produit </a>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>
                    <div class="table-responsive" v-else>
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Date création</th>
                                    <th scope="col">Libellé</th>
                                    <th scope="col">Catégorie</th>
                                    <th scope="col">Prix unitaire</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in filteredProducts" :key="index">
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.created_at}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @{{ data.name  }}
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.category.name}} </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.unit_price}}F</span>

                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.stock }}</span>
                                    </td>
                                    <td>
                                        <span class="badge" :class="{'bg-success-transparent':data.stock > 0, 'bg-danger-transparent':data.stock === 0 }">@{{data.stock > 0 ? 'En stock' : 'Sans stock' }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button title="Editer" class="btn btn-sm btn-info-light btn-icon"><i class="ri-edit-2-fill"></i></button>
                                            <button title="Supprimer" @click.prevent="deleteProduct(data.id)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
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

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/product.js') }}"></script>
@endsection
