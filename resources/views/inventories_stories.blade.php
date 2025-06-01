@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppInventory" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Historique d'inventaires</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">inventaires</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        @if (Auth::user()->role=="admin")
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Liste des inventaires
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="date" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>
                    <div class="table-responsive" v-else>
                        <div class="table-responsive" v-if="filteredInventories.length">
                            <table class="table text-nowrap table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-primary-transparent text-primary">Date création</th>
                                        <th class="bg-primary-subtle text-primary">Status</th>
                                        <th class="bg-primary-transparent text-primary">Date validation</th>
                                        <th class="bg-primary-subtle text-primary">Créer par</th>
                                        <th class="bg-primary-transparent text-primary">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(data, index) in filteredInventories" :key="data.id">
                                        <!-- Ligne principale -->
                                        <tr>
                                            <td>
                                                <div class="fs-14 d-flex">
                                                    <i class="ri-calendar-2-line me-2"></i>
                                                    <span>@{{ data.created_at }}</span>
                                                </div>
                                            </td>
                                            <td class="fw-bold">
                                                <span class="badge" :class="{'bg-warning-transparent text-warning':data.status == 'pending', 'bg-success-transparent text-success':data.status == 'validated', 'bg-danger-transparent text-danger':data.status == 'cancelled' }">@{{  data.status }}</span>
                                            </td>
                                            <td class="fw-bold">
                                                @{{ data.status =='pending' ? '---' : data.updated_at }}
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
                                                    <!-- <button class="btn btn-sm btn-bd-primary"><i class="ri-file-pdf-fill"></i>Exporter</button> -->
                                                    <button @click.prevent="deleteInventory(data.id)" title="Annuler l'inventaire en cours..." v-if="data.status =='pending'" class="btn btn-sm btn-icon btn-danger-transparent">
                                                        <span v-if="load_id == data.id"
                                                            class="spinner-border spinner-border-sm"
                                                            style="height:12px; width:12px"></span>
                                                        <i v-else class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Ligne de détails collapsible -->
                                        <tr :id="`details-${data.id}`" class="collapse bg-light animated-collapse">
                                            <td colspan="5">
                                                <table class="table mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="bg-primary-transparent text-primary">Produit</th>
                                                            <th class="bg-primary-transparent text-primary">Qté théorique</th>
                                                            <th class="bg-primary-transparent text-primary">Qté réelle</th>
                                                            <th class="bg-primary-transparent text-primary">Difference</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="item in data.lines" :key="item.id">
                                                            <td>@{{ item.product.name }}</td>
                                                            <td>@{{ item.theoretical_qty }}</td>
                                                            <td>@{{ item.real_qty }}</td>
                                                            <td>@{{ item.difference }}</td>
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
        @endif
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/inventory.js') }}"></script>
@endsection

@section("styles")
<style>
    .animated-collapse {
        transition: all 0.35s ease;
        overflow: hidden;
    }
</style>
@endsection
