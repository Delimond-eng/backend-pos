@extends("layouts.app")

@section("content")
<div class="container-fluid"> <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Création produit</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Création produit</li>
                </ol>
            </nav>
        </div>
    </div> <!-- Page Header Close --> <!-- Start::row-1 -->
    <div class="row" id="AppProduct" v-cloak>
        <div class="col-xl-12">
            <div class="card custom-card">
                <form @submit.prevent="submitForm" id="form" class="card-body add-products p-0">
                    @csrf
                    <div class="p-4">
                        <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erreur !</strong>@{{ error }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>
                        <div v-if="result" class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Succès !</strong>@{{ result }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>
                        <div class="row gx-5">
                            <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0 border-0">
                                    <div class="card-header mb-3 pb-2">
                                        <h2 class="card-title">Produit infos</h2>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-12"> 
                                                <label for="product-name-add" class="form-label">Libellé <sup class="text-danger">*</sup></label>
                                                <input type="text" name="name" class="form-control" id="product-name-add" placeholder="Libellé du produit..."> 
                                            </div>
                                            <div class="col-xl-6"> 
                                                <label for="cat" class="form-label">Catégorie <sup class="text-danger">*</sup></label> 
                                                <select name="category_id" id="cat" class="form-select">
                                                    <option value="" selected hidden>--Sélectionnez une catégorie--</option>
                                                    <option :value="cat.id" v-for="(cat, i) in categories" :key="i">@{{ cat.name }}</option>
                                                </select>
                                            </div>
                                            <div class="col-xl-6"> <label for="product-actual-price" class="form-label">Prix unitaire de vente</label> <input type="number" name="unit_price" class="form-control" id="product-actual-price" placeholder="0.00F"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0 border-0">
                                    <div class="card-header mb-3 pb-2">
                                        <h2 class="card-title">Approvisionnement(Optionnel)</h2>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="row gy-3">
                                            <div class="col-xl-6"> <label for="product-dealer-price" class="form-label">Prix d'achat unitaire</label> <input type="number" name="unit_price2" class="form-control" id="product-dealer-price" placeholder="0.00F"> </div>
                                            <div class="col-xl-6"> <label for="product-discount" class="form-label">Quantité</label> <input type="number" name="quantity" class="form-control" id="product-discount" placeholder="0"> </div>
                                            <div class="col-xl-6"> <label for="product-type" class="form-label">Date(optionnel)</label> <input type="date" name="date" class="form-control" id="product-type"> </div>
                                            <div class="col-xl-6"> <label for="product-type" class="form-label">Fournisseur(optionnel)</label> <input type="text" name="supplier_name" class="form-control" id="product-type" placeholder="Fournisseur(optionnel)..."> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 border-top border-block-start-dashed d-sm-flex justify-content-end"> 
                        <button class="btn btn-danger-light m-1">Annuler<i class="bi bi-circle-half ms-2"></i></button> 
                        <button class="btn btn-success m-1" :disabled="isLoading"><i class="bi bi-plus me-2"></i>Valider & sauvegarder <span v-if="isLoading" class="spinner-border spinner-border-sm ms-2"></span></button> 
                    </div>
                </form>
            </div>
        </div>
    </div> <!--End::row-1 -->
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/product.js') }}"></script>
@endsection