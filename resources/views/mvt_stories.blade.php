@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppProduct" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Historique d'approvisionnement</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Historique</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Liste des mouvements
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
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
                                    <th scope="col">Date approvisionnement</th>
                                    <th scope="col">Produit</th>
                                    <th scope="col">Quantité</th>
                                    <th scope="col">P.A Unitaire</th>
                                    <th scope="col">P.A Total</th>
                                    <th scope="col">Fournisseur</th>
                                    <th scope="col">Fait par</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stories as $s)
                                <tr>
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>{{ $s->created_at}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $s->purchase->date  }}
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{$s->product->name}} </span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $s->quantity}}</span>

                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $s->unit_price }}F</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $s->purchase->total_amount }}F</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $s->purchase->supplier_name ?? '---' }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">{{ $s->purchase->user->name }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button title="Editer" class="btn btn-sm btn-info-light btn-icon"><i class="ri-edit-2-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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
