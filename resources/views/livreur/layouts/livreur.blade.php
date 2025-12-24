<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Espace Livreur')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak]{display:none!important}
        body, input, select, textarea, button { font-family: 'Times New Roman', Times, serif; }
        html, body { height: 100%; }
    </style>
    </head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
                            <a href="{{ route('livreur.commandes.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('livreur.commandes.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700 text-white"><i class="fas fa-motorcycle"></i></span>
                <div>
                    <div class="text-sm text-blue-100">Espace</div>
                    <div class="text-lg font-bold">Livreur</div>
                            <a href="{{ route('livreur.profil') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('livreur.profil') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('accueil') }}" class="text-sm px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white"><i class="fas fa-store mr-1"></i> Boutique</a>
                <form method="POST" action="{{ route('livreur.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm px-3 py-2 rounded bg-red-50 text-red-600 hover:bg-red-100"><i class="fas fa-sign-out-alt mr-1"></i> Déconnexion</button>
                </form>
            </div>
        </div>
    </header>

    <main class="w-full px-0 flex-1 min-h-0">
        <div class="grid grid-cols-[280px,1fr] gap-0 h-full min-h-0">
            <aside class="h-full overflow-auto">
                <nav class="bg-white rounded-none shadow p-4 h-full">
                    <h2 class="text-lg font-bold mb-4">Navigation</h2>
                    <ul class="space-y-1 text-sm">
                        <li><a href="{{ route('livreur.dashboard') }}" class="flex items-center px-3 py-2 rounded {{ request()->routeIs('livreur.dashboard') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50' }}"><i class="fas fa-list mr-2"></i> Mes livraisons</a></li>
                        <li><a href="{{ route('livreur.profil') }}" class="flex items-center px-3 py-2 rounded {{ request()->routeIs('livreur.profil') ? 'bg-blue-50 text-blue-700' : 'hover:bg-gray-50' }}"><i class="fas fa-user-cog mr-2"></i> Mon profil</a></li>
                    </ul>
                </nav>
            </aside>
            <section class="h-full overflow-auto px-6 py-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
                @endif
                @yield('livreur-content')
            </section>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 text-gray-600">
        <div class="container mx-auto px-4 py-4 text-sm flex items-center justify-between">
            <div>© {{ date('Y') }} Mini Amazon — Espace Livreur</div>
            <div class="space-x-4">
                <a href="{{ route('livreur.dashboard') }}" class="hover:text-blue-700">Mes livraisons</a>
                <a href="{{ route('livreur.profil') }}" class="hover:text-blue-700">Mon profil</a>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
