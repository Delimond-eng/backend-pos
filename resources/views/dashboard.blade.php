@extends('layouts.app')


@section('content')
<div class="container-fluid">
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
        <div class="col-xxl-6 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0"> <span class="rounded p-3 bg-primary-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white primary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <g>
                                                <rect fill="none" height="24" width="24"></rect>
                                                <path d="M18,6h-2c0-2.21-1.79-4-4-4S8,3.79,8,6H6C4.9,6,4,6.9,4,8v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8C20,6.9,19.1,6,18,6z M12,4c1.1,0,2,0.9,2,2h-4C10,4.9,10.9,4,12,4z M18,20H6V8h2v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8h4v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8 h2V20z"></path>
                                            </g>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Sales</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> 14,732 </span> </div>
                                    <div> <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+4.2%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> <span class="rounded p-3 bg-secondary-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white secondary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0,0h24v24H0V0z" fill="none"></path>
                                            <g>
                                                <path d="M19.5,3.5L18,2l-1.5,1.5L15,2l-1.5,1.5L12,2l-1.5,1.5L9,2L7.5,3.5L6,2v14H3v3c0,1.66,1.34,3,3,3h12c1.66,0,3-1.34,3-3V2 L19.5,3.5z M15,20H6c-0.55,0-1-0.45-1-1v-1h10V20z M19,19c0,0.55-0.45,1-1,1s-1-0.45-1-1v-3H8V5h11V19z"></path>
                                                <rect height="2" width="6" x="9" y="7"></rect>
                                                <rect height="2" width="2" x="16" y="7"></rect>
                                                <rect height="2" width="6" x="9" y="10"></rect>
                                                <rect height="2" width="2" x="16" y="10"></rect>
                                            </g>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Expenses</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> $28,346.00 </span> </div>
                                    <div> <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0"> <span class="rounded p-3 bg-success-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white success" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                                            <path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Visitors</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> 1,29,368 </span> </div>
                                    <div> <span class="fs-12 mb-0">Decreased by <span class="badge bg-danger-transparent text-danger mx-1">-7.6%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0"> <span class="rounded p-3 bg-warning-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white warning" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                                            <path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Orders</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> 35,367 </span> </div>
                                    <div> <span class="fs-12 mb-0">Increased by <span class="badge bg-success-transparent text-success mx-1">+2.5%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-6 col-xl-12">
            <div class="row">
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon px-0"> <span class="rounded p-3 bg-primary-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white primary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <g>
                                                <rect fill="none" height="24" width="24"></rect>
                                                <path d="M18,6h-2c0-2.21-1.79-4-4-4S8,3.79,8,6H6C4.9,6,4,6.9,4,8v12c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V8C20,6.9,19.1,6,18,6z M12,4c1.1,0,2,0.9,2,2h-4C10,4.9,10.9,4,12,4z M18,20H6V8h2v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8h4v2c0,0.55,0.45,1,1,1s1-0.45,1-1V8 h2V20z"></path>
                                            </g>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Sales</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> 14,732 </span> </div>
                                    <div> <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+4.2%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon secondary  px-0"> <span class="rounded p-3 bg-secondary-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white secondary" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0,0h24v24H0V0z" fill="none"></path>
                                            <g>
                                                <path d="M19.5,3.5L18,2l-1.5,1.5L15,2l-1.5,1.5L12,2l-1.5,1.5L9,2L7.5,3.5L6,2v14H3v3c0,1.66,1.34,3,3,3h12c1.66,0,3-1.34,3-3V2 L19.5,3.5z M15,20H6c-0.55,0-1-0.45-1-1v-1h10V20z M19,19c0,0.55-0.45,1-1,1s-1-0.45-1-1v-3H8V5h11V19z"></path>
                                                <rect height="2" width="6" x="9" y="7"></rect>
                                                <rect height="2" width="2" x="16" y="7"></rect>
                                                <rect height="2" width="6" x="9" y="10"></rect>
                                                <rect height="2" width="2" x="16" y="10"></rect>
                                            </g>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Expenses</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> $28,346.00 </span> </div>
                                    <div> <span class="fs-12 mb-0">Increase by <span class="badge bg-success-transparent text-success mx-1">+12.0%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon success px-0"> <span class="rounded p-3 bg-success-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white success" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                                            <path d="M12 6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2m0 10c2.7 0 5.8 1.29 6 2H6c.23-.72 3.31-2 6-2m0-12C9.79 4 8 5.79 8 8s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm0 10c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Visitors</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> 1,29,368 </span> </div>
                                    <div> <span class="fs-12 mb-0">Decreased by <span class="badge bg-danger-transparent text-danger mx-1">-7.6%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-sm-6 col-md-6 col-xl-6">
                    <div class="card custom-card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xxl-3 col-xl-2 col-lg-3 col-md-3 col-sm-4 col-4 d-flex align-items-center justify-content-center ecommerce-icon warning px-0"> <span class="rounded p-3 bg-warning-transparent"> <svg xmlns="http://www.w3.org/2000/svg" class="svg-white warning" height="24px" viewBox="0 0 24 24" width="24px" fill="#000000">
                                            <path d="M0 0h24v24H0V0z" fill="none"></path>
                                            <path d="M15.55 13c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.37-.66-.11-1.48-.87-1.48H5.21l-.94-2H1v2h2l3.6 7.59-1.35 2.44C4.52 15.37 5.48 17 7 17h12v-2H7l1.1-2h7.45zM6.16 6h12.15l-2.76 5H8.53L6.16 6zM7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"></path>
                                        </svg> </span> </div>
                                <div class="col-xxl-9 col-xl-10 col-lg-9 col-md-9 col-sm-8 col-8 px-0">
                                    <div class="mb-2">Total Orders</div>
                                    <div class="text-muted mb-1 fs-12"> <span class="text-dark fw-semibold fs-20 lh-1 vertical-bottom"> 35,367 </span> </div>
                                    <div> <span class="fs-12 mb-0">Increased by <span class="badge bg-success-transparent text-success mx-1">+2.5%</span> this month</span> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection