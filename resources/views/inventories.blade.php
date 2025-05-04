@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppSales" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Inventaire des produits</h1>
        <div class="ms-md-1 ms-0" v-if="!currentInventory">
            <nav>
                <button :disabled="isLoading" @click.prevent="startInventory" class="btn btn-primary-gradient"> <i class="ri-bar-chart-2-fill"></i> Commencez un inventaire <span v-if="isLoading"
                                                                        class="spinner-border spinner-border-sm ms-2"
                                                                        style="height:12px; width:12px"></span></button>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title"> Liste des produits</div>
                    <div class="d-flex flex-wrap">
                        <div class="me-3 my-1"> <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit..." aria-label=".form-control-sm example"> </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 personal-upcoming-events">
                        <li class="border-bottom border-1 pb-1" v-for="(data, i) in filteredProducts" :key="i">
                            <div class="d-flex align-items-center">
                                <label :for="`Checked-${data.id}`" class="flex-fill">
                                    <span class="fw-bold"> @{{ data.name }} </span>
                                </label>
                                <div>
                                    <div class="btn-list">
                                        <div class="form-check form-check-md">
                                            <input class="form-check-input form-checked-secondary border-info-subtle" type="checkbox" :id="`Checked-${data.id}`"
                                            :checked="selectedProductIds.includes(data.id)"
                                            @change="toggleProductSelection(data)">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div class="text-info"> Veuillez sélectionner des produits pour inventaires<i class="bi bi-arrow-right ms-2 fw-semibold"></i> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card custom-card">
                <div class="card-header d-sm-flex d-block">
                    <div class="card-title">Inventaire en cours...</div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Produit</th>
                                    <th scope="col">Qté théorique</th>
                                    <th scope="col">Qté physique</th>
                                    <th scope="col">Ecart</th>
                                    <th scope="col">Valeur</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(line, i) in inventoryLines" :key="line.id">
                                    <td>@{{ i + 1 }}</td>
                                    <td>@{{ line.name }}</td>
                                    <td>@{{ line.stock_global ?? 0 }}</td>
                                    <td>
                                        <input
                                            type="number"
                                            placeholder="0"
                                            class="form-control form-control-sm w-50"
                                            v-model.number="line.real_quantity" @input="() => {}">
                                    </td>
                                    <td>
                                        <span :class="{'text-success': getInventoryGap(line) > 0,'text-danger': getInventoryGap(line) < 0,}">@{{ getInventoryGap(line) }}</span>
                                    </td>
                                    <td>
                                        @{{ getInventoryValue(line) }}F
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer border-top-0" v-if="inventoryLines.length">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <div class="d-flex flex-column">
                                <span>Total écart: <strong>@{{ getTotalGap() }}</strong> </span>
                                <span>Valeur totale : <strong>@{{ getTotalValue() }}</strong>  F</span>
                            </div>
                        </div>
                        <div>
                            <button type="button" @click.prevent="inventoryLines=[]; selectedProductIds=[]" class="btn btn-dark-light bg-dark-transparent">Annuler</button>
                            <button class="btn btn-success">Valider & Ajuster</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/inventory.js') }}"></script>
@endsection