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
                    <div class="d-flex align-items-center">
                        <div class="me-3 mb-3 mb-sm-0">
                            <input v-model="search" class="form-control form-control-sm border-primary-subtle" type="text" placeholder="Recherche produit " aria-label=".form-control-sm example">
                        </div>
                        <div class="mb-3 mb-sm-0">
                            <input class="form-control form-control-sm border-primary-subtle" type="date" required />
                        </div>
                        <span class="text-capitalize mx-1">--</span>
                        <div class="me-2 mb-3 mb-sm-0">
                            <input class="form-control form-control-sm border-primary-subtle" type="date" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm me-2">
                            <i class="ri-filter-line"></i>
                            Filtrer
                        </button>
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
                                    <th scope="col" class="bg-primary-subtle text-primary">Date création</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Date approvisionnement</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Produit</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Quantité</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Prix achat Unitaire</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Total</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Fournisseur</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Fait par</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">
                                        <button title="actualiser" class="btn btn-sm btn-outline-secondary btn-icon"><i class="ri-refresh-line"></i></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stories as $s)
                                <tr>
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>{{ $s->created_at->format("d/m/y h:i")}}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ $s->purchase->date }}
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
                                            <button title="Editer" class="btn btn-sm btn-info btn-icon me-1"><i class="ri-eye-2-fill"></i></button>
                                            <button title="Delete" @click.prevent="deleteApprov('{{ $s->product->id }}', '{{ $s->quantity }}', '{{ $s->id }}')" class="btn btn-sm btn-danger btn-icon contact-delete">
                                                <span v-if="load_id == '{{ $s->id }}'" class="spinner-border spinner-border-sm" style="height:12px; width:12px"></span><i v-else class="ri-delete-bin-line"></i> </button>

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
