<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Rapid Tech POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset("pos/js/main/vue2.js") }}"></script>
    <link rel="stylesheet" href="{{asset('pos/js/libs/sweetalert2/sweetalert2.min.css')}}">
    <link rel="stylesheet" href="{{ asset("pos/css/pos.css") }}">
    <link href="{{asset('assets/icon-fonts/icons.css')}}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="app">
        <header class="app-header">
            <div class="logo">
                <i class="ri-shopping-bag-fill"></i>
                <span style="font-weight: 900;">Rapid Tech</span>
            </div>
            <div class="user-menu" @click="dropdownOpen = !dropdownOpen">
                <div class="avatar">{{ substr(strtoupper(Auth::user()->name), 0,1) }}</div>
                <span class="username">{{ Auth::user()->name }} <br> <small style="font-size: 11px;">@{{ daySale  }}F</small></span>
                <i class="ri-arrow-drop-down-fill"></i>
                <div class="dropdown" :class="{ active: dropdownOpen }">
                    <!-- ✅ Balance en entête -->
                    <div class="dropdown-header">
                        <i class="ri-wallet-2-line"></i>
                        <small style="color: #eeeeee;">Solde des ventes journalières</small>
                        <span class="balance">@{{ daySale  }}F</span>
                    </div>
                    <a href="#"><i class="ri-close-line"></i> Fermer Session</a>
                    <a href="{{ url("/") }}"><i class="ri-user-2-line"></i> Administration</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" hidden>
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="ri-logout-box-line"></i> Déconnexion</a>
                </div>
            </div>
        </header>

        @yield("content")
    </div>
     <!-- SWEETALERT JS -->
    <script src="{{asset('pos/js/libs/sweetalert2/sweetalert2.min.js')}}"></script>
    <script type="module" src="{{ asset("pos/js/app.js") }}"></script>
</body>

</html>