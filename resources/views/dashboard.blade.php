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
                            </div>
                        </div>
                        <div class="col-sm-4 col-auto my-auto">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

