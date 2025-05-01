@extends('layouts.app')


@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb d-print-none">
        <h1 class="page-title fw-semibold fs-18 mb-0">Invoice preview</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">preview</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card d-print-block">
                <div class="card-header d-md-flex d-block">
                    <div class="h5 mb-0 d-sm-flex d-block align-items-center">
                        <div class="avatar avatar-sm">
                            <img src="{{ asset('assets/images/logos/logo.png') }}" height="40" alt="logo">
                        </div>
                        <div class="ms-sm-2 ms-0 mt-sm-0 mt-2 ">
                            <div class="h6 fw-semibold mb-0">FACTURE NO : <span class="text-primary"># 0{{ $data->id }} </span> <span class="badge d-d-print-none ms-4 {{ $data->facture_status == 'paie' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $data->facture_status }}
                                </span></div>

                        </div>
                    </div>
                    <div class="ms-auto mt-md-0 mt-2 d-print-none">
                        <button class="btn btn-sm btn-secondary me-1" onclick="javascript:window.print();">Print<i class="ri-printer-line ms-1 align-middle d-inline-block"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row gy-3">
                        <div class="col-xl-12">
                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                    <p class="text-muted mb-2">
                                        DATE : {{ $data->facture_create_At}}
                                    </p>
                                    <p class="fw-bold mb-1">
                                        ZANDO PRINT GRAPHIC
                                    </p>
                                    <p class="mb-1 text-muted">
                                        Local 05, Nouvelle Gallerie, C/Gombe
                                    </p>
                                    <p class="mb-1 text-muted">
                                        TEL : +243 81 84 85 879
                                    </p>
                                    <p class="text-muted">RCCM : CD/KNG/RCCM/21-A-02337 ID.NAT. :01-C1700-N84832W</p>
                                </div>
                                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ms-auto mt-sm-0 mt-3">
                                    <p class="text-muted mb-2">
                                        Facture à :
                                    </p>
                                    <p class="fw-bold mb-1">
                                        {{ $data->client->client_nom }}
                                    </p>
                                    <p class="text-muted">
                                        {{ $data->client->client_tel }}
                                    </p>

                                </div>
                            </div>
                        </div>

                        <div class="col-xl-12">
                            <div class="table-responsive">
                                <table class="table nowrap text-nowrap border mt-2">
                                    <thead>
                                        <tr>
                                            <th>DESIGNATION</th>
                                            <th>NATURE</th>
                                            <th>QTE/MESURE</th>
                                            <th>PU</th>
                                            <th>TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->details as $detail )
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $detail->facture_detail_libelle }}

                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-muted">
                                                    {{ $detail->facture_detail_nature }}
                                                </div>
                                            </td>
                                            <td class="product-quantity-container">
                                                {{ $detail->facture_detail_qte }}
                                            </td>
                                            <td>
                                                {{ $detail->facture_detail_pu }}
                                                {{ $detail->facture_detail_devise }}
                                            </td>
                                            <td>
                                                {{ $detail->facture_detail_pu * $detail->facture_detail_qte }}
                                                {{ $detail->facture_detail_devise }}
                                            </td>
                                        </tr>

                                        @endforeach

                                        <tr>
                                            <td colspan="3"></td>
                                            <td colspan="2">
                                                <table class="table table-sm text-nowrap mb-0 table-borderless">
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0">Sous Total :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-15">$ {{ $data->facture_montant }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0">Equivalent en CDF :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-15">{{ $data->facture_montant * $currencie }}</p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0">TVA <span class="text-danger">(16%)</span> :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-15">$ 0.00</p>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <th scope="row">
                                                                <p class="mb-0 fs-14">Total :</p>
                                                            </th>
                                                            <td>
                                                                <p class="mb-0 fw-semibold fs-16 text-success">$ {{ $data->facture_montant }}</p>
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
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
