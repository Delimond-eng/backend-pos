@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppPayment" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Paiements</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Paiements</li>
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
                        liste des paiements
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm" type="text" placeholder="Recherche paiement par client" aria-label=".form-control-sm example">
                        </div>
                        {{-- <div class="dropdown">
                            <a href="javascript:void(0);" class="btn btn-light btn-sm btn-wave show" data-bs-toggle="dropdown" aria-expanded="true">
                                Filtrer par<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu" style="position: absolute; inset: auto 0px 0px auto; margin: 0px; transform: translate3d(0px, -30.6667px, 0px);" data-popper-placement="top-end">
                                <li><a class="dropdown-item" href="javascript:void(0);">Tout</a></li>
                                <li><a class="dropdown-item" href="javascript:void(0);">Date de création</a></li>
                            </ul>
                        </div>  --}}

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
                                    <th scope="col">ID Facture</th>
                                    <th scope="col">Montant à payer</th>
                                    <th scope="col">Montant payé</th>
                                    <th scope="col">Reste à payer</th>
                                    <th scope="col">Client</th>
                                    <th scope="col">Perçu</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allPayments" :key="index">
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.operation_create_At }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-14"># 0@{{data.facture_id}}</div>
                                    </td>
                                    <td>
                                        <div class="fs-14">@{{ data.facture_montant}} $</div>

                                    </td>
                                    <td>
                                        <div class="fs-14">@{{ data.totalPay }} $</div>

                                    </td>
                                    <td>
                                        <div class="fs-14">@{{ data.facture_montant-data.totalPay }} $</div>
                                    </td>
                                    <td>
                                        <div class="fs-14">@{{data.client_nom}}</div>
                                    </td>
                                    <td>
                                        <div class="fs-14">@{{data.user_name}}</div>

                                    </td>

                                    <td>
                                        <div class="btn-list">
                                            <button @click.prevent="loadDetail(data.facture_id)" title="Voir détails" class="btn btn-sm btn-primary-light btn-icon"><span v-if="load_id_detail == data.facture_id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-eye-line"></i></button>
                                            <button title="Supprimer" @click.prevent="deleteAllPayment(data.facture_id)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
                                                <span v-if="load_id == data.facture_id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button>
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Paiement détails</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-0">
                    <div class="row">
                        <div class="col-xxl-6 col-xl-12 col-md-12">
                            <div class="card custom-card shadow-none border-0">
                                <div class="card-header d-sm-flex d-block">
                                    <div class="card-title">Liste des détails du paiement sélectionné</div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive" v-if="details">
                                        <table class="table text-nowrap table-hover px-4">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Libellé</th>
                                                    <th scope="col">Montant payé
                                                    </th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(data, index) in details" :key="index">
                                                    <td>@{{ data.operation_create_At }}</td>
                                                    <td>@{{ data.operation_libelle }}</td>
                                                    <td>@{{ data.operation_montant}} @{{ data.operation_devise }}</td>
                                                    <td><button title="Supprimer" @click.prevent="deleteOne(data.id, index)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
                                                            <span v-if="delete_id == data.id" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button></td>
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
<script type="module" src="{{ asset('assets/js/scripts/payment.js') }}"></script>
@endsection
