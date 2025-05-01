@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppInvoice">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Création facture</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Facturation</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <form @submit.prevent="createFacture" class="card custom-card">
                <div class="card-header d-md-flex d-block">
                    <div class="h5 mb-0 d-sm-flex d-block align-items-center">
                        <div>
                            <img src="{{ asset('assets/images/logos/logo.png') }}" height="40" alt="logo">
                        </div>

                    </div>
                    <!-- <div class="ms-auto mt-md-0 mt-2">
                        <button type="button" class="btn btn-sm btn-primary me-2">Valider & imprimer<i class="ri-printer-line ms-1 align-middle d-inline-block"></i></button>
                    </div> -->
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                    <p class="dw-semibold mb-2">
                                        Date: (optionnel)
                                    </p>
                                    <div class="row gy-2">
                                        <div class="col-xl-12">
                                            <input type="date" v-model="form.facture_create_At" class="form-control form-control-light">
                                        </div>

                                    </div>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ms-auto mt-sm-0 mt-3">
                                    <p class="dw-semibold mb-2">
                                        Facture à :
                                    </p>
                                    <div class="row gy-2">
                                        <div class="col-xl-12">
                                            <select2 @select="form.client_id = $event.id" :placeholder="'Veuillez selectionner un client...'" :id="'clientSelect'" :name="'client_select'" :options="allClients" v-model="form.client_id"></select2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table class="table nowrap text-nowrap border mt-3 w-100">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 30%;">PRODUIT</th>
                                            <th scope="col" style="width: 25%;">NATURE</th>
                                            <th scope="col" style="width: 13%;">QTE</th>
                                            <th scope="col" style="width:20%;">PU</th>
                                            <th scope="col" style="width: 13%;">TOT</th>
                                            <th scope="col" style="width: 5%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in form.facture_details" :key="index">
                                            <td>
                                                <select2 @select="onSelectItem(item, $event)" :placeholder="'Veuillez selectionner un item...'" :id="'fieldSelect_'+index" :name="'field_'+index" :options="allProducts" :required="true"></select2>
                                            </td>
                                            <td>
                                                <select class="form-select form-select-lg" @change="onSelectNature(item)" v-model="item.nature" required>
                                                    <option selected hidden :value="null">Nature...</option>
                                                    <option v-for="(data, index) in item.natures" :value="data" :key="data.id">
                                                        @{{ data.item_nature_libelle }}
                                                    </option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" v-model="item.facture_detail_qte" class="form-control form-control-light" placeholder="Entrer la quantité ou mesure" required>
                                            </td>

                                            <td class="d-flex">
                                                <input v-model="item.facture_detail_pu" class="form-control form-control-light flex-fill me-1" placeholder="Prix unitaire" type="text">
                                                <select class="form-control" style="width:80px" v-model="item.facture_detail_devise">
                                                    <option selected hidden value="">Devise</option>
                                                    <option value="USD">USD</option>
                                                    <option value="CDF">CDF</option>
                                                </select>
                                            </td>
                                            <td><input class="form-control form-control-light" placeholder="Sous tot." type="text" readOnly :value="isNaN(parseInt(item.facture_detail_qte) * parseFloat(item.facture_detail_pu)) ? 0 : parseInt(item.facture_detail_qte) * parseFloat(item.facture_detail_pu)"></td>
                                            <td>
                                                <button v-show="form.facture_details.length > 1" type="button" @click.prevent="deleteField(index)" class="btn btn-sm btn-icon btn-danger-light"><i class="ri-close-line"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table nowrap text-nowrap border-0 table-borderless mt-0">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 40%;"></th>
                                            <th scope="col" style="width: 30%;"></th>
                                            <th scope="col" style="width: 20%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="6" class="border-bottom-0 border-top-0"><button type="button" class="btn btn-primary-light" @click.prevent="addField"><i class="bi bi-plus-lg"></i> Ajouter item</button></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"></td>
                                            <td colspan="2">
                                                <table class="table table-sm text-nowrap mb-0 table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="fw-semibold">Sub Total :</div>
                                                            </th>
                                                            <td>
                                                                <input type="text" readOnly class="form-control form-control-light invoice-amount-input" placeholder="Enter Amount" :value="invoiceTotal+' $'">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="fw-semibold">Equivalent en CDF:</div>
                                                            </th>
                                                            <td>
                                                                <input type="text" readOnly class="form-control form-control-light invoice-amount-input" placeholder="Enter Amount" :value="cdfAmount">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <div class="fw-semibold">Tva <span class="text-danger">(16%)</span> :</div>
                                                            </th>
                                                            <td>
                                                                <input type="text" class="form-control form-control-light invoice-amount-input" readOnly placeholder="Enter Amount" value="0">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div v-if="error" class="alert alert-danger overflow-hidden p-0" role="alert">
                        <div class="p-3 bg-danger text-fixed-white d-flex justify-content-between">
                            <h6 class="aletr-heading mb-0 text-fixed-white">Echec de traitement de la requête !</h6>
                            <button type="button" class="btn-close p-0 text-fixed-white" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>
                        <hr class="my-0">
                        <div class="p-3">
                            <p class="mb-0">@{{ error }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="button" @click.prevent="cleanFields" class="btn btn-light me-1"><i class="ri-close-circle-line me-1 align-middle d-inline-block"></i>Annuler</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-success"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Sauvegarder & imprimer</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/invoice.js') }}"></script>
@endsection
