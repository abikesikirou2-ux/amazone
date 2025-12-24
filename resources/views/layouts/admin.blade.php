<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration - Mini Amazon')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body, input, select, textarea, button { font-family: 'Times New Roman', Times, serif; }
        html, body { height: 100%; }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Top admin bar (no store nav) -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700 text-white">
                    <i class="fas fa-shield-alt"></i>
                </span>
                <div>
                    <div class="text-sm text-blue-100">Espace</div>
                    <div class="text-lg font-bold">Administration</div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('accueil') }}" class="text-sm px-3 py-2 rounded bg-blue-700 hover:bg-blue-600 text-white">
                    <i class="fas fa-store mr-1"></i> Voir la boutique
                </a>
                @auth
                    <div class="hidden md:block text-sm text-blue-100">{{ Auth::user()->name }}</div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm px-3 py-2 rounded bg-red-50 text-red-600 hover:bg-red-100">
                            <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main content with vertical sidebar -->
    <main class="w-full px-0 flex-1 min-h-0">
        <div class="grid grid-cols-[280px,1fr] gap-0 h-full min-h-0">
            <aside class="h-full overflow-auto">
                <nav class="bg-white rounded-none shadow p-4 h-full">
                    <h2 class="text-lg font-bold mb-4">Navigation</h2>
                    <ul class="space-y-1 text-sm">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-chart-line mr-2"></i> Tableau de bord
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.produits.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.produits.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-box mr-2"></i> Gestion produit
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.categories.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-list mr-2"></i> Gestion catégorie
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.commandes.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.commandes.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-receipt mr-2"></i> Gestion commande
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.livreurs.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.livreurs.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-motorcycle mr-2"></i> Gestion livreur
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reduction.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.reduction.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-percent mr-2"></i> Bonus / Réduction
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.coupons.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.coupons.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-ticket-alt mr-2"></i> Codes promos clients
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.points-relais.index') }}" class="flex items-center px-3 py-2 rounded transition transform hover:scale-105 {{ request()->routeIs('admin.points-relais.*') ? 'bg-blue-50 text-blue-700' : 'hover:bg-blue-50 hover:text-blue-700' }}">
                                <i class="fas fa-map-marker-alt mr-2"></i> Points relais
                            </a>
                        </li>
                        <li class="pt-2 mt-2 border-t">
                            <button x-data x-on:click="$dispatch('open-modal', 'admin-profile')" class="w-full text-left flex items-center px-3 py-2 rounded hover:bg-gray-50">
                                <i class="fas fa-user-cog mr-2"></i> Mon profil
                            </button>
                        </li>
                    </ul>
                </nav>
            </aside>
            <section class="h-full overflow-auto px-6 py-6">
                @yield('admin-content')
            </section>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 text-gray-600">
        <div class="container mx-auto px-4 py-4 text-sm flex items-center justify-between">
            <div>© {{ date('Y') }} Mini Amazon — Administration</div>
            <div class="space-x-4">
                <a href="{{ route('accueil') }}" class="hover:text-blue-700">Boutique</a>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-700">Dashboard</a>
            </div>
        </div>
    </footer>

    <!-- Modal Profil Admin -->
    <div x-data="{ open: false }"
         x-on:open-modal.window="if($event.detail === 'admin-profile') open = true"
         x-on:keydown.escape.window="open = false"
         x-show="open" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/40" x-on:click="open=false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">Mon profil</h3>
                <button class="text-gray-500 hover:text-gray-700" x-on:click="open=false"><i class="fas fa-times"></i></button>
            </div>

            @if (session('status') === 'profile-updated')
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">Profil mis à jour.</div>
            @endif
            @if (session('status') === 'password-updated')
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">Mot de passe mis à jour.</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Formulaire informations du profil -->
                <form method="post" action="{{ route('profile.update') }}" class="space-y-3">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="return_to" value="{{ request()->fullUrl() }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                        @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                        @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-4 py-2">Enregistrer</button>
                        <button type="button" class="text-gray-600 hover:text-gray-800" x-on:click="open=false">Fermer</button>
                    </div>
                </form>

                <!-- Formulaire changement de mot de passe -->
                <form method="post" action="{{ route('password.update') }}" class="space-y-3">
                    @csrf
                    @method('put')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                        <input type="password" name="password" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2">Mettre à jour</button>
                        <button type="button" class="text-gray-600 hover:text-gray-800" x-on:click="open=false">Fermer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
