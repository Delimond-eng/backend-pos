@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppDashboard" v-cloak>
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Tableau de bord</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tableau de bord</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-md-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-body">
                    <div class="row gap-3 gap-sm-0">
                        <div class="col-sm-8 col-12">
                            <div class="">
                                <h4 class="fw-semibold mb-2">Bienvenue <span class="text-primary">{{ Auth::user()->name }}</span> </h4>

                                <p class="mb-4 text-muted fs-14 op-7">
                                    Veuillez profiter de toutes les fonctionnalités qui sont liées à votre profil utilisateur
                                </p>
                                <div class="btn-list pt-1">
                                    <button class="btn btn-primary btn-wave m-1 waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#client-modal">Créez un nouveau client</button>

                                    <a href="{{ route('invoice') }}" class="btn btn-outline-primary btn-wave m-1 waves-effect waves-light">Créez une nouvelle facture</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-auto my-auto">
                            <div class="featured-nft">
                                <img src="{{ asset('assets/images/logos/logo.png') }}" alt="logo">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6" v-show="!isDataLoading">
            <div class="row">
                <div v-if="dashCounts.length > 0" class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top">
                                <div class="me-3 lh-1">
                                    <span class="avatar avatar-lg bg-primary">
                                        <svg class="svg-white" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0 0h24v24H0V0z" fill="none" />
                                            <path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM9 4h2v5l-1-.75L9 9V4zm9 16H6V4h1v9l3-2.25L13 13V4h5v16z" /></svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">Fac. journalières</p>
                                    <h5 class="fw-semibold mb-2">@{{ dashCounts[0].count }}</h5>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="dashCounts.length > 0" class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top">
                                <div class="me-3 lh-1">
                                    <span class="avatar avatar-lg bg-secondary">
                                        <svg class="svg-white" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0,0h24v24H0V0z" fill="none" />
                                            <g>
                                                <path d="M19.5,3.5L18,2l-1.5,1.5L15,2l-1.5,1.5L12,2l-1.5,1.5L9,2L7.5,3.5L6,2v14H3v3c0,1.66,1.34,3,3,3h12c1.66,0,3-1.34,3-3V2 L19.5,3.5z M15,20H6c-0.55,0-1-0.45-1-1v-1h10V20z M19,19c0,0.55-0.45,1-1,1s-1-0.45-1-1v-3H8V5h11V19z" />
                                                <rect height="2" width="6" x="9" y="7" />
                                                <rect height="2" width="2" x="16" y="7" />
                                                <rect height="2" width="6" x="9" y="10" />
                                                <rect height="2" width="2" x="16" y="10" />
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">Fac. en attente</p>
                                    <h5 class="fw-semibold mb-2">@{{ dashCounts[1].count }}</h5>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="dashCounts.length > 0" class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top">
                                <div class="me-3 lh-1">
                                    <span class="avatar avatar-lg bg-success">
                                        <svg class="svg-white" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <g>
                                                <path d="M0,0h24v24H0V0z" fill="none" />
                                            </g>
                                            <g>
                                                <g>
                                                    <path d="M21.41,11.41l-8.83-8.83C12.21,2.21,11.7,2,11.17,2H4C2.9,2,2,2.9,2,4v7.17c0,0.53,0.21,1.04,0.59,1.41l8.83,8.83 c0.78,0.78,2.05,0.78,2.83,0l7.17-7.17C22.2,13.46,22.2,12.2,21.41,11.41z M12.83,20L4,11.17V4h7.17L20,12.83L12.83,20z" />
                                                    <circle cx="6.5" cy="6.5" r="1.5" />
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">Total de clients</p>
                                    <h5 class="fw-semibold mb-2">@{{ dashCounts[2].count }}</h5>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="dashCounts.length > 0" class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-top">
                                <div class="me-3 lh-1">
                                    <span class="avatar avatar-lg bg-warning">
                                        <svg class="svg-white" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <g>
                                                <rect fill="none" height="24" width="24" />
                                            </g>
                                            <g>
                                                <path d="M12,2C6.48,2,2,6.48,2,12s4.48,10,10,10s10-4.48,10-10S17.52,2,12,2z M12,20c-4.41,0-8-3.59-8-8c0-4.41,3.59-8,8-8 s8,3.59,8,8C20,16.41,16.41,20,12,20z M12.89,11.1c-1.78-0.59-2.64-0.96-2.64-1.9c0-1.02,1.11-1.39,1.81-1.39 c1.31,0,1.79,0.99,1.9,1.34l1.58-0.67c-0.15-0.44-0.82-1.91-2.66-2.23V5h-1.75v1.26c-2.6,0.56-2.62,2.85-2.62,2.96 c0,2.27,2.25,2.91,3.35,3.31c1.58,0.56,2.28,1.07,2.28,2.03c0,1.13-1.05,1.61-1.98,1.61c-1.82,0-2.34-1.87-2.4-2.09L8.1,14.75 c0.63,2.19,2.28,2.78,3.02,2.96V19h1.75v-1.24c0.52-0.09,3.02-0.59,3.02-3.22C15.9,13.15,15.29,11.93,12.89,11.1z" />
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div>
                                    <p class="mb-1 text-muted">Fac. Payés</p>
                                    <h5 class="fw-semibold mb-2">@{{ dashCounts[3].count }}</h5>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6" v-if="dayCount !==''" v-show="!isDataLoading">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                        <div>
                            <p class="mb-0 text-muted">Balance journalière</p>
                            <h5 class="fw-semibold">$@{{ dayCount }}</h5>
                        </div>
                        <div>
                            <span class="avatar avatar-md bg-primary-transparent">
                                <svg class="svg-primary" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                    <path d="M0 0h24v24H0V0z" fill="none" />
                                    <path d="M21 7.28V5c0-1.1-.9-2-2-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-2.28c.59-.35 1-.98 1-1.72V9c0-.74-.41-1.37-1-1.72zM20 9v6h-7V9h7zM5 19V5h14v2h-6c-1.1 0-2 .9-2 2v6c0 1.1.9 2 2 2h6v2H5z" />
                                    <circle cx="16" cy="12" r="1.5" /></svg>
                            </span>
                        </div>
                    </div>
                    <p v-if="currencie !== ''" class="mb-0 text-muted fs-11 op-7">Taux du jour: <span class="text-danger fw-semibold">@{{ currencie }} <small>CDF</small> </span></p>

                    <div id="nft-balance-chart" class="px-3 pt-2 pb-0"></div>
                    <div class="d-grid mt-3">
                        @if(Auth::user()->role=="admin")
                        <button class="btn btn-success-light btn-wave" data-bs-toggle="modal" data-bs-target="#currencie-modal">Mettre à jour le taux</button>

                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                <span class="spinner-border text-primary"></span>
                <span class="text-muted mt-1">Chargement...</span>
            </div>
        </div>
    </div>

    <div class="modal effect-slide-in-right" id="currencie-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"> Mise à jour taux</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <form @submit.prevent="updateCurrencie" class="row gy-2">
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Taux du jour</label>
                            <input type="text" v-model="currencie" class="form-control" id="name" placeholder="Saisir le nouveau taux du jour" required>
                        </div>
                        <div class="col-xl-12">
                            <button :disabled="isLoading" type="submit" class="btn btn-primary w-100">
                                <span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>
                                Mettre à jour le taux
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

</div>
<div id="AppClient">
    <div class="modal effect-flip-horizontal" id="client-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <form @submit.prevent="submitForm" class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Création d'un client</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-6">
                            <label for="name" class="form-label">Nom du client</label>
                            <input type="text" v-model="form.name" class="form-control" id="name" placeholder="Nom complet" required>
                        </div>
                        <div class="col-xl-6">
                            <label for="task-id" class="form-label">Téléphone</label>
                            <input type="text" v-model="form.phone" class="form-control" id="phone" placeholder="Téléphone">
                        </div>
                        <div class="col-xl-12">
                            <label for="text-area" class="form-label">Adresse(optionnelle)</label>
                            <textarea class="form-control" v-model="form.address" id="adresse" rows="2" placeholder="Adresse"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-success"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span>Créer </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/dashboard.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/client.js') }}"></script>
@endsection
