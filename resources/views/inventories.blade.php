@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppInventory" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Inventaires</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">inventaires</li>
                </ol>
            </nav>
        </div>
    </div>


    <!-- Start::row-1 -->
    <div class="row d-flex justify-content-start">
        <div class="col-xl-3">
            <div class="card custom-card">
                <div class="card-header d-sm-flex d-block">
                    <div class="card-title">Total général</div>
                </div>
                <div class="card-body">
                    <div class="d-md-flex d-block flex-wrap align-items-center justify-content-between">
                        <div class="flex-fill">

                        </div>
                        <div class="d-flex flex-wrap align-items-center mt-md-0 justify-content-evenly">
                            <div class=" text-md-end">
                                <span class="text-primary fw-bold">$ @{{ totalGen }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::row-1 -->


    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Les données de l'inventaire
                    </div>
                    <form @submit.prevent="loadInventories" class="d-flex align-items-center">
                        <div class="me-2 mb-3 mb-sm-0" v-if="filterWord=='mois'">
                            <select class="form-select form-select-sm" v-model="selectMonth" required>
                                <option selected hidden value="">Mois</option>
                                <option v-for="(data,i) in mois" :value="data.value" :key="i">@{{ data.label }}</option>
                            </select>
                        </div>
                        <div class="me-2 mb-3 mb-sm-0" v-if="filterWord=='mois'">
                            <select class="form-select form-select-sm" v-model="selectYear" required>
                                <option selected hidden value="">Année</option>
                                <option v-for="(data,i) in years" :value="data" :key="i">@{{ data }}</option>
                            </select>
                        </div>

                        <div class="mb-3 mb-sm-0" v-if="filterWord=='date'">
                            <input class="form-control form-control-sm" v-model="date_start" type="date" required />
                        </div>
                        <span v-if="filterWord=='date'" class="text-capitalize mx-1">--</span>
                        <div class="me-2 mb-3 mb-sm-0" v-if="filterWord=='date'">
                            <input class="form-control form-control-sm" type="date" v-model="date_end" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm me-2" v-if="filterWord !== 'all'">
                            <i class="ri-filter-line"></i>
                            Filtrer
                        </button>
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave show" data-bs-toggle="dropdown" aria-expanded="true">
                                Filtrer par<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu" style="position: absolute; inset: auto 0px 0px auto; margin: 0px; transform: translate3d(0px, -30.6667px, 0px);" data-popper-placement="top-end">
                                <li><a class="dropdown-item" href="javascript:void(0);" @click.prevent="filterWord='all'; loadInventories();">Tout</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" @click.prevent="filterWord='mois';">Mois</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);" @click.prevent="filterWord='date';">Date</a></li>
                            </ul>
                        </div>

                    </form>
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
                                    <th scope="col">Montant total</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allInventories" :key="index">
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.operation_create_At }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-14">$ @{{data.total_amount}}</div>
                                    </td>

                                    <td>
                                        <div class="btn-list">
                                            <button @click.prevent="viewDetails(data.operation_create_At)" title="Voir détails" class="btn btn-sm btn-primary-light btn-icon"><span v-if="load_id == data.operation_create_At" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-eye-line"></i></button>
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
    {{-- Modal allow to view all payment details --}}
    <div class="modal effect-slide-in-bottom" id="detail-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Détails des opérations</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-0">
                    <div class="row">
                        <div class="col-xxl-6 col-xl-12 col-md-12">
                            <div class="card custom-card shadow-none border-0">
                                <div class="card-header d-sm-flex d-block">
                                    <div class="card-title">Liste des détails de la date sélectionnée</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" v-if="details">
                                        <table class="table text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Libellé</th>
                                                    <th scope="col">Type</th>
                                                    <th scope="col">Montant</th>
                                                    <th scope="col">Mode de paiement</th>
                                                    <th scope="col">Facture ID</th>
                                                    <th scope="col">Perçu par</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(data, index) in allDetails" :key="index">
                                                    <td>@{{ data.operation_create_At }}</td>
                                                    <td>@{{ data.operation_libelle }}</td>
                                                    <td>@{{ data.operation_type }}</td>
                                                    <td>@{{ data.operation_montant}} @{{ data.operation_devise }}</td>
                                                    <td>@{{ data.operation_mode}}</td>
                                                    <td># 0@{{ data.facture_id }}</td>
                                                    <td v-if="data.facture"> <span v-if="data.facture.user">@{{ data.facture.user.name }}</span> </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- End create modal  --}}

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/inventory.js') }}"></script>
@endsection
