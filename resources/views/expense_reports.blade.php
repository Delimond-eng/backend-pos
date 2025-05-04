@extends('layouts.app')


@section('content')
<div class="container-fluid" id="AppExpenses" v-cloak>

    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Rapport des Dépenses</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">App</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dépenses</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Rapport de toutes les dépenses
                    </div>
                    <div class="d-sm-flex">
                        <div class="me-2">
                            <div class="input-group">
                                <select v-model="filter" class="form-control form-control-sm border-primary-subtle">
                                    <option value="" class="text-muted" hidden selected>--Rechercher par motif--</option>
                                    <option v-for="(e, index) in expenseTypes" :value="e.id" :key="index">@{{ e.name }}</option>
                                </select>
                                <button class="btn btn-sm btn-primary" @click="filter=''"><i class="ri-refresh-line"></i></button>
                            </div>
                        </div>
                        <div class="dropdown"><a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-info btn-sm btn-wave waves-effect waves-light"> Exporter<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i></a> <ul role="menu" class="dropdown-menu"><li><a href="javascript:void(0);" class="dropdown-item">Excel</a></li> <li><a href="javascript:void(0);" class="dropdown-item">PDF</a></li></ul></div>
                    </div>
                </div>
                <div class="card-body">
                    <div v-if="isDataLoading" class="d-flex flex-column justify-content-center align-items-center p-5">
                        <span class="spinner-border text-primary"></span>
                        <span class="text-muted mt-1">Chargement...</span>
                    </div>
                    <div class="table-responsive" v-else>
                        <table class="table text-nowrap table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="bg-primary-transparent text-primary">Date</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Montant</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Motif</th>
                                    <th scope="col" class="bg-primary-subtle text-primary">Déscription</th>
                                    <th scope="col" class="bg-primary-transparent text-primary">Créer par</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(data, index) in allExpenses" :key="index">
                                    <td>
                                        <div class="fs-14 d-flex">
                                            <i class="ri-calendar-2-line me-2"></i>
                                            <span>@{{ data.date}}</span>
                                        </div>
                                    </td>
                                    <td class="fw-bold">
                                        @{{ data.amount  }} F
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-info">@{{ data.type.name}}</span>
                                    </td>
                                    <td>
                                        <span class="fw-semibold">@{{ data.description ?? '---'}} </span>
                                    </td>
                                    
                                    <td>
                                        <span class="fw-semibold">@{{ data.user.name }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="expense-create-modal" aria-modal="true" role="dialog">
        <form @submit.prevent="addExpense" class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" v-if="form.expense_id">Modification dépense</h6>
                    <h6 class="modal-title" v-else>Nouvelle dépense</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4">
                    <div v-if="error" class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Erreur !</strong>@{{ error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="row gy-2">
                        <div class="col-xl-6">
                            <label for="name" class="form-label">Date <sup>(optionnel)</sup></label>
                            <input type="date" v-model="form.date" class="form-control border-secondary-subtle">
                        </div>
                        <div class="col-xl-6">
                            <label for="name" class="form-label">Montant</label>
                            <input type="number" v-model="form.amount" placeholder="0.00F" class="form-control border-secondary-subtle">
                        </div>
                        <div class="col-xl-12">
                            <label for="name" class="form-label">Motif</label>
                            <select class="form-select border-secondary-subtle" v-model="form.expense_type_id">
                                <option value="" selected hidden>--Sélectionnez un motif--</option>
                                <option v-for="(e, index) in expenseTypes" :value="e.id" :key="index">@{{ e.name }}</option>
                            </select>
                        </div>

                        <div class="col-xl-12">
                            <label for="name" class="form-label">Déscription</label>
                            <textarea v-model="form.description" class="form-control border-secondary-subtle" placeholder="déscription..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" :disabled="isLoading" class="btn btn-primary"><span v-if="isLoading" class="spinner-border spinner-border-sm me-2"></span> <span v-if="form.expense_id">Modifier la dépense</span> <span v-else>Valider & sauvegarder</span></button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/main/vue2.js') }}"></script>
<script type="module" src="{{ asset('assets/js/scripts/expense.js') }}"></script>
@endsection