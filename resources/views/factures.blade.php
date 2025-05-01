@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppInvoice" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Factures</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Factures</li>
                </ol>
            </nav>
        </div>
    </div>


    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-md-flex d-block flex-wrap align-items-center justify-content-between">
                        <div class="flex-fill">
                            <ul class="nav nav-pills nav-style-3" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" @click.prevent="viewAllFactures('all')" data-bs-toggle="tab" role="tab" aria-current="page" href="#all" aria-selected="true">Toutes</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" @click.prevent="viewAllFactures('pending')" data-bs-toggle="tab" role="tab" aria-current="page" href="#pending" aria-selected="true">En attente</a>

                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" @click.prevent="viewAllFactures('completed')" data-bs-toggle="tab" role="tab" aria-current="page" href="#completed" aria-selected="true">Payées</a>
                                </li>
                            </ul>
                        </div>
                        <div class="d-flex flex-wrap align-items-center mt-md-0 mt-3 justify-content-evenly gap-4">
                            <div class="text-md-end">
                                <span class="d-block fw-semibold">Balance journalière</span>
                                <span class="text-primary fw-bold">$ @{{ daySum }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End::row-1 -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Toutes les factures
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm" type="text" placeholder="Recherche facture de client " aria-label=".form-control-sm example">
                        </div>

                        <a href="{{ route('invoice') }}" class="btn btn-primary btn-sm"> <i class="ri-add-line"></i> Créez une nouvelle facture </a>
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
                                    <th scope="col">Client</th>
                                    <th scope="col">Id.Facture</th>
                                    <th scope="col">Montant à payer</th>
                                    <th scope="col">Montant payé</th>
                                    <th scope="col">status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allFactures" :key="index">
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.facture_create_At }}</span>

                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column" v-if="data.client" class="fs-14">
                                            <span>@{{ data.client.client_nom }}</span>
                                            <small class="text-muted">@{{ data.client.client_tel}}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ '0'+data.id+'' }} </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.facture_montant}}$</span>

                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ totPayment(data.paiements) }} $</span>
                                    </td>
                                    <td>
                                        <span class="badge" :class="{'bg-success-transparent':data.facture_status ==='paie', 'bg-danger-transparent':data.facture_status ==='en attente'}">@{{ data.facture_status }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a :href="'/invoicepreview/'+data.id" title="voir détails" class="btn btn-sm btn-warning-light btn-icon"><i class="ri-eye-line"></i></a>
                                            <button title="Percevoir un paiement" @click.prevent="selectedFacture = data" data-bs-toggle="modal" data-bs-target="#pay-modal" class="btn btn-sm btn-info-light btn-icon"><i class="ri-money-dollar-box-line"></i></button>
                                            <button v-if="data.paiements.length ===0" title="Supprimer" @click.prevent="deleteFacture(data.id)" class="btn btn-sm btn-danger-light btn-icon contact-delete">
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


    {{-- Modal allow to pay facture --}}
    <div class="modal fade" id="pay-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="makePayment" class="modal-dialog modal-dialog-centered">

            <div class="modal-content">
                <div class="modal-header">
                    <h6 v-if="selectedFacture" class="modal-title">Paiement de la facture <span class="badge ms-3" :class="{'bg-success':selectedFacture.facture_status ==='paie', 'bg-danger':selectedFacture.facture_status ==='en attente'}">@{{ selectedFacture.facture_status }}</span></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-6">
                                    <table class="table table-sm text-nowrap mb-0" v-if="selectedFacture">
                                        <tbody>
                                            <tr>
                                                <th scope="row">
                                                    <p class="mb-0">Facture N° :</p>
                                                </th>
                                                <td>
                                                    <p class="mb-0 fw-bold fs-15"> # <span v-if="selectedFacture">0@{{ selectedFacture.id }}</span></p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <p class="mb-0 fs-14">Client : </p>
                                                </th>
                                                <td class="d-flex flex-column">
                                                    <p class="fw-bold mb-1 fs-14" v-if="selectedFacture">@{{ selectedFacture.client.client_nom }}</p>
                                                    <small class="text-muted" v-if="selectedFacture">@{{ selectedFacture.client.client_tel }}</s>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-xl-6">
                                    <table class="table table-sm text-nowrap mb-0" v-if="selectedFacture">
                                        <tbody>
                                            <tr>
                                                <th scope="row">
                                                    <p class="mb-0">Montant à payer :</p>
                                                </th>
                                                <td>
                                                    <p class="mb-0 fw-bold fs-15"> @{{ selectedFacture.facture_montant}} USD</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <p class="mb-0">Montant payé :</p>
                                                </th>
                                                <td>
                                                    <p class="mb-0 fw-bold fs-15"> 0 USD</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <p class="mb-0 fs-14 text-primary">Restes à payer : </p>
                                                </th>
                                                <td>
                                                    <p class="fw-bold fs-14 mb-0 text-primary" v-if="selectedFacture">@{{ totPayment(selectedFacture.paiements) }} USD</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="price" class="form-label">Montant payé *</label>
                                    <div class="d-flex">
                                        <input type="text" v-model="formPay.operation_montant" class="form-control me-1 flex-fill" id="name" placeholder="Entrez le montant payé...ex: 30">
                                        <select class="form-select ms-2" style="width:100px">
                                            <option value="USD" selected>USD</option>
                                            <option value="CDF">CDF</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-12">
                                    <label for="qte" class="form-label">Mode de paiement *</label>
                                    <select class="form-select" v-modal="formPay.operation_mode" required>
                                        <option value="Cash" selected>Cash</option>
                                        <option value="Paiement mobile">Paiement mobile</option>
                                        <option value="Virement">Virement</option>
                                        <option value="Chèque">Chèque</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Compte *</label>
                            <select class="form-select" v-model="formPay.compte_id" required>
                                <option value="" selected hidden>Sélectionnez un compte</option>
                                <option v-for="(item, index) in allComptes" :value="item.id" :key="index">@{{ item.compte_libelle }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" v-if="selectedFacture">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button v-if="selectedFacture.facture_status !=='paie'" type="submit" :disabled="isLoading2" class="btn btn-success"><span v-if="isLoading2" class="spinner-border spinner-border-sm me-2"></span>Valider & sauvegarder le paiement</button>
                </div>
            </div>
        </form>
    </div>
    {{-- End create modal  --}}

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/invoice.js') }}"></script>
@endsection
