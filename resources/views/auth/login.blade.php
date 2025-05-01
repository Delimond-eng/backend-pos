@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center authentication authentication-basic h-100">
        <div class="col-xxl-4 col-xl-5 col-lg-5 col-md-6 col-sm-8 col-12">
            <div class="my-2 d-flex justify-content-center">
                <img src="{{ asset('assets/images/logos/logo.png') }}" height="100" alt="logo" class="desktop-logo">
                <img src="{{ asset('assets/images/logos/logo.png') }}" alt="logo" class="desktop-dark">
            </div>
            <div class="card custom-card">
                <div class="card-body p-5">
                    <p class="mb-4 text-muted op-7 fw-normal text-center">Entrez vos identifiants pour vous connecter !</p>
                    <form method="POST" action="{{ route('login') }}" class="row gy-3">
                        @csrf
                        <div class="col-xl-12">
                            <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" required placeholder="Nom d'utilisateur">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-xl-12 mb-1">

                            <div class="input-group">
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Mot de passe">
                                <button class="btn btn-light" type="button" id="button-view-pwd"><i class="ri-eye-off-line align-middle"></i></button>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <div class="mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <div class="d-flex justify-content-between">
                                        <label class="form-check-label text-muted fw-normal" for="remember">
                                            Garder ma session ?
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12 d-grid mt-2">
                            <button type="submit" class="btn btn-lg btn-dark">Connecter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('assets/js/show-password.js')}}"></script>
@endsection
