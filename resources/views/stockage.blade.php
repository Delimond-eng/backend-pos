@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppStockage" v-cloak>


    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Stockage</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">stockage</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-body">
                    <div class="row gap-3 gap-sm-0">
                        <div class="col-sm-12 col-12">
                            <div class="">
                                <h4 class="fw-semibold mb-2">Bienvenue <span class="text-primary">{{ Auth::user()->name }}</span> </h4>

                                <p class="mb-4 text-muted fs-14 op-7">
                                    Veuillez gérer le stockage de vos produits en effectuant des opérations d'approvisionnement et de déstockage !
                                </p>
                                <div class="btn-list">
                                    <button class="btn btn-primary btn-wave m-1 waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#append-modal">Approvisionnement</button>
                                    <button class="btn btn-outline-danger btn-wave m-1 waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#reduce-modal">Déstockage</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Situation globale du stockage
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <div class="me-2">
                            <input v-model="search" class="form-control form-control-sm" type="text" placeholder="Recherche produit..." aria-label=".form-control-sm example">
                        </div>
                        <button type="button" class="btn btn-sm btn-primary ms-2 align-items-center d-inline-flex" data-bs-toggle="modal" data-bs-target="#product-modal"><i class="ti ti-plus me-1 fw-semibold"></i>Ajout nouveau produit</button>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>

                    <div v-else class="table-responsive">
                        <table class="table text-nowrap table-bordered border-primary">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Libellé Produit</th>
                                    <th scope="col">Total des entrées</th>
                                    <th scope="col">Total des sorties</th>
                                    <th scope="col">Solde total</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Etat</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allStocks" :key="index">
                                    <th scope="row">
                                        @{{ data.produit_create_At }}
                                    </th>
                                    <td>
                                        @{{ data.produit_libelle }}
                                    </td>
                                    <td>
                                        <span class="text-success">@{{ data.totalEntree }} <i class="ti ti-arrow-bear-right"></i></span>
                                    </td>
                                    <td>
                                        <span class="text-danger">@{{ data.totalSortie }} <i class="ri-arrow-down-line"></i></span>
                                    </td>
                                    <td>
                                        <span class="text-primary fw-bold">@{{ data.solde }} </span>
                                    </td>
                                    <td>
                                        <span :class="{'badge bg-warning': (data.solde > 0 && data.solde <= 10) || data.totalEntree===0, 'badge bg-danger': data.solde == 0 && data.totalEntree !== 0, 'badge bg-success': data.solde > 10}">
                                            @{{ data.status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress progress-xs">
                                            <div class="progress-bar" :class="{'bg-warning': data.percent > 0 && data.percent <= 30, 'bg-danger': data.percent == 0, 'bg-success': data.percent > 10}" :style="{ width: data.percent + '%' }" aria-valuenow="32" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>

                                    </td>
                                    <td>
                                        <button @click.prevent="showDetail(data)" title="Voir détails" class="btn btn-sm btn-info-light btn-icon"><i class="ri-eye-line"></i></button>
                                        <button @click.prevent="showProduct(data)" title="Approvisionnement" class="btn btn-sm btn-primary-light btn-icon"><i class="ri-add-line"></i></button>
                                        <button @click.prevent="showReduce(data)" title="Déstockage" class="btn btn-sm btn-warning-light btn-icon"><i class="bx bx-minus-circle"></i></button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal allow to create new product  --}}
    <div class="modal fade" id="product-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="createProduct" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Création produit du stock</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Libelle produit <sup class="text-danger">*</sup></label>
                            <input type="text" v-model="formProduit.produit_libelle" :readOnly="formProduit.id !== undefined" class="form-control" id="name" placeholder="Entrez le libellé du produit à stocker..." required>
                        </div>
                        <div class="col-xl-12" v-if="formProduit.id">
                            <label for="name" class="form-label text-primary fw-bold">Solde stock</label>
                            <input type="text" :value="formProduct.solde" readOnly class="form-control form-control-primary" id="name">
                        </div>
                        <div class="col-xl-12">
                            <div class="alert alert-primary d-flex align-items-center mt-3" role="alert">
                                <svg class="flex-shrink-0 me-2 svg-primary" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" /></svg>
                                <div>
                                    <strong>Approvisionnement !</strong> (optionnel)
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <label for="qte" class="form-label">Quantité</label>
                                    <input type="number" v-model="formProduit.entree.entree_qte" class="form-control" id="name" placeholder="Entrez le libellé du produit à stocker...">
                                </div>
                                <div class="col-lg-12">
                                    <label for="price" class="form-label">Prix d'achat</label>
                                    <div class="d-flex">
                                        <input type="number" v-model="formProduit.entree.entree_prix_achat" class="form-control me-1 flex-fill" id="name" placeholder="prix d'achat">
                                        <select class="form-select" v-model="formProduit.entree.entree_prix_devise" style="width:100px">
                                            <option value="CDF" selected>CDF</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                </div>
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
    {{-- End create modal  --}}


    {{-- Modal allow to Add new stock to product --}}
    <div class="modal fade" id="append-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="appendProduct" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Approvisionnement stock</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12" v-if="formAppend.produit !== undefined">
                            <label for="name" class="form-label">Libelle produit</label>
                            <input type="text" v-model="formAppend.produit.produit_libelle" readOnly class="form-control" id="name" placeholder="Entrez le libellé du produit à stocker..." required>
                        </div>
                        <div class="col-xl-12" v-if="formAppend.produit !== undefined">
                            <label for="name" class="form-label text-primary fw-bold">Solde stock</label>
                            <input type="text" :value="formAppend.produit.solde" readOnly class="form-control form-control-primary" id="name">
                        </div>
                        <div class="col-xl-12" v-if="formAppend.produit === undefined">
                            <label for="name" class="form-label">Produit</label>
                            <select class="form-select" v-model="formAppend.produit_id" required>
                                <option :value="null" selected hidden>Sélectionnez un produit</option>
                                <option v-for="(item, index) in allProducts" :value="item.id" :key="index">@{{ item.text }}</option>
                            </select>
                            {{-- <select2 :placeholder="'Veuillez selectionner un produit...'" :id="'my-select2'" :name="'example'" :options="allProducts" v-model="formAppend.produit_id"></select2>  --}}
                        </div>

                        <div class="col-xl-12">
                            <div class="alert alert-primary d-flex align-items-center mt-3" role="alert">
                                <svg class="flex-shrink-0 me-2 svg-primary" xmlns="http://www.w3.org/2000/svg" height="1.5rem" viewBox="0 0 24 24" width="1.5rem" fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path d="M11 7h2v2h-2zm0 4h2v6h-2zm1-9C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" /></svg>
                                <div>
                                    <strong>Approvisionnement !</strong> (optionnel)
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <label for="qte" class="form-label">Quantité</label>
                                    <input type="number" v-model="formAppend.entree_qte" class="form-control" id="name" placeholder="Entrez le libellé du produit à stocker...">
                                </div>
                                <div class="col-lg-12">
                                    <label for="price" class="form-label">Prix d'achat</label>
                                    <div class="d-flex">
                                        <input type="number" v-model="formAppend.entree_prix_achat" class="form-control me-1 flex-fill" id="name" placeholder="prix d'achat">
                                        <select class="form-select" v-model="formAppend.entree_prix_devise" style="width:100px">
                                            <option value="CDF" selected>CDF</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-primary"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Mettre à jour le stock </button>
                </div>
            </div>
        </form>
    </div>
    {{-- End create modal  --}}

    {{-- Modal allow to reduce stocks --}}
    <div class="modal fade" id="reduce-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="reduceProduct" class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Approvisionnement stock</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-12" v-if="formReduce.produit !== undefined">
                            <label for="name" class="form-label">Libelle produit</label>
                            <input type="text" v-model="formReduce.produit.produit_libelle" readOnly class="form-control" id="name" placeholder="Entrez le libellé du produit à stocker..." required>
                        </div>
                        <div class="col-xl-12" v-if="formReduce.produit !== undefined">
                            <label for="name" class="form-label text-primary fw-bold">Solde stock</label>
                            <input type="text" :value="formReduce.produit.solde" readOnly class="form-control form-control-light" id="name">
                        </div>
                        <div class="col-xl-12" v-if="formReduce.produit === undefined">
                            <label for="name" class="form-label">Produit</label>
                            <select class="form-select" v-model="formReduce.produit_id" required>
                                <option :value="null" selected hidden>Sélectionnez un produit</option>
                                <option v-for="(item, index) in allProducts" :value="item.id" :key="index">@{{ item.text }}</option>
                            </select>
                            {{-- <select2 :placeholder="'Veuillez selectionner un produit...'" :id="'my-select2'" :name="'example'" :options="allProducts" v-model="formAppend.produit_id"></select2>  --}}
                        </div>

                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <label for="price" class="form-label">Quantité</label>
                                    <input type="number" v-model="formReduce.sortie_qte" class="form-control me-1 flex-fill" id="name" placeholder="Entrez la quantité à sortir...ex: 10">
                                </div>
                                <div class="col-xl-12">
                                    <label for="qte" class="form-label">Motif</label>
                                    <textarea class="form-control" placeholder="Veuillez entrer le motif de la sortie stock..." v-model="formReduce.sortie_motif" required></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-warning"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Valider & sauvegarder</button>
                </div>
            </div>
        </form>
    </div>
    {{-- End create modal  --}}

    {{-- Modal allow to reduce stocks --}}
    <div class="modal effect-slide-in-bottom" id="detail-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Détail du stock</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-0">
                    <div class="row">
                        <div class="col-xxl-6 col-xl-12 col-md-12">
                            <div class="card custom-card shadow-none border-0">
                                <div class="card-header d-sm-flex d-block">
                                    <div class="card-title">@{{ detail.produit_libelle }}</div>

                                    <div class="tab-menu-heading border-0 p-0 ms-auto mt-sm-0 mt-2">
                                        <div class="tabs-menu-task me-3">
                                            <ul class="nav nav-tabs panel-tabs-task border-0" role="tablist">
                                                <li><a href="javascript:void(0);" class="me-1 active" data-bs-toggle="tab" data-bs-target="#Active" role="tab" aria-selected="true">Approvisionnement <small>(Entrées)<small /> </a></li>
                                                <li><a href="javascript:void(0);" data-bs-toggle="tab" data-bs-target="#Complete" role="tab" aria-selected="false">Déstockage <small>(Sorties)<small /></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="tab-content p-0">
                                        <div class="tab-pane active p-0 border-0" id="Active">
                                            <div class="table-responsive" v-if="detail.entrees">
                                                <table class="table text-nowrap table-hover px-4">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Quantité</th>
                                                            <th scope="col">Prix d'achat</th>
                                                            <th scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(data, index) in detail.entrees" :key="index">
                                                            <td>@{{ data.entree_create_At }}</td>
                                                            <td>@{{ data.entree_qte }}</td>
                                                            <td>@{{ data.entree_prix_achat}} @{{ data.entree_prix_devise }}</td>
                                                            <td><button title="Voir détails" class="btn btn-sm btn-danger-light btn-icon"><i class="ri-delete-bin-line"></i></button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane p-0 border-0" id="Complete">
                                            <div class="table-responsive" v-if="detail.sorties">
                                                <table class="table text-nowrap table-hover px-4">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Motif</th>
                                                            <th scope="col">Quantité</th>
                                                            <th scope="col"></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr v-for="(data, index) in detail.sorties" :key="index">
                                                            <td>@{{ data.sortie_create_At }}</td>
                                                            <td>@{{ data.sortie_motif }} </td>
                                                            <td>@{{ data.sortie_qte}}</td>
                                                            <td><button title="Voir détails" class="btn btn-sm btn-danger-light btn-icon"><i class="ri-delete-bin-line"></i></button></td>

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
        </div>
    </div>
    {{-- End create modal  --}}
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/stockage.js') }}"></script>
@endsection
