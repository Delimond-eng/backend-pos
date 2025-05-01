<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <title>Rapid Tech POS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/vue@2.7.14/dist/vue.js"></script>
    <link rel="stylesheet" href="{{ asset("pos/css/pos.css") }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div id="app">
        <header class="app-header">
            <div class="logo">
                <i class="fas fa-shopping-bag"></i>
                <span style="font-weight: 900;">Rapid Tech</span>
            </div>
            <div class="user-menu" @click="dropdownOpen = !dropdownOpen">
                <div class="avatar">{{ substr(strtoupper(Auth::user()->name), 0,1) }}</div>
                <span class="username">{{ Auth::user()->name }}</span>
                <i class="fas fa-chevron-down"></i>
                <div class="dropdown" :class="{ active: dropdownOpen }">
                    <!-- ✅ Balance en entête -->
                    <div class="dropdown-header">
                        <i class="fas fa-wallet"></i>
                        <span class="balance">Solde : 45000.00F</span>
                    </div>
                    <a href="#"><i class="fas fa-door-closed"></i> Fermer Session</a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" hidden>
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
                </div>
            </div>
        </header>

        @yield("content")
    </div>
    <script type="module" src="{{ asset("pos/js/app.js") }}"></script>
</body>

</html>