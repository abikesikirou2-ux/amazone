<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mini Amazon - E-commerce')</title>
    
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
        body { min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1 1 auto; }
    </style>
    
    @yield('styles')
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white shadow-lg sticky top-0 z-50">
        <!-- Top Bar -->
        <div class="bg-blue-900 py-2">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center space-x-4">
                        <a href="tel:+2290154253797" class="hover:text-blue-200 transition" title="Appeler">
                            <i class="fas fa-phone mr-1"></i> +229 01 54 25 37 97
                        </a>
                        <a href="https://mail.google.com/mail/?view=cm&fs=1&to=mimiamazone555@gmail.com" target="_blank" rel="noopener noreferrer" class="hover:text-blue-200 transition">
                            <i class="fas fa-envelope mr-1"></i> mimiamazone555@gmail.com
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('compte') }}" class="hover:text-blue-200 transition">
                                <i class="fas fa-user mr-1"></i> Mon compte
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="hover:text-blue-200 transition">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Déconnexion
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-blue-200 transition">
                                <i class="fas fa-sign-in-alt mr-1"></i> Se connecter
                            </a>
                            <a href="{{ route('register') }}" class="hover:text-blue-200 transition">
                                <i class="fas fa-user-plus mr-1"></i> Inscription
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('accueil') }}" class="flex items-center space-x-2">
                    <i class="fas fa-shopping-bag text-3xl"></i>
                    <span class="text-2xl font-bold">Mini Amazon</span>
                </a>

                <!-- Search Bar -->
                <form action="{{ route('recherche') }}" method="GET" class="hidden md:flex flex-1 max-w-2xl mx-8"
                      x-data="{
                        term: '{{ str_replace("'", "\'", request('q')) }}',
                        open: false,
                        loading: false,
                        items: [],
                        highlighted: -1,
                        fetch() {
                          if (!this.term || this.term.length < 2) { this.items = []; this.open = false; return; }
                          this.loading = true;
                          fetch('{{ route('api.suggestions') }}?term=' + encodeURIComponent(this.term))
                            .then(r => r.json())
                            .then(d => { this.items = d; this.open = d.length > 0; this.highlighted = -1; })
                            .finally(() => this.loading = false);
                        },
                        select(i) {
                          const item = typeof i === 'number' ? this.items[i] : i;
                          if (!item) { this.submit(); return; }
                          this.term = item.nom; this.$refs.input.value = item.nom; this.submit();
                        },
                        move(delta) {
                          if (!this.open || this.items.length === 0) return;
                          this.highlighted = (this.highlighted + delta + this.items.length) % this.items.length;
                        },
                        submit() { this.open = false; this.$el.submit(); }
                      }"
                      @keydown.escape.window="open = false">
                    <div class="relative w-full">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="Rechercher des produits..." 
                            class="w-full px-4 py-3 rounded-l-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300"
                            x-ref="input"
                            x-model="term"
                            @input.debounce.300ms="fetch()"
                            @keydown.arrow-down.prevent="move(1)"
                            @keydown.arrow-up.prevent="move(-1)"
                            @keydown.enter.prevent="highlighted >= 0 ? select(highlighted) : submit()"
                        >
                        <button type="submit" class="absolute right-0 top-0 h-full px-6 bg-orange-500 hover:bg-orange-600 rounded-r-lg transition">
                            <i class="fas fa-search text-white"></i>
                        </button>

                        <!-- Suggestions Dropdown -->
                        <div x-cloak x-show="open" class="absolute left-0 right-14 mt-1 bg-white text-gray-800 rounded-lg shadow-2xl z-50 overflow-hidden">
                            <template x-if="loading">
                                <div class="px-4 py-3 text-sm text-gray-500">Recherche...</div>
                            </template>
                            <template x-for="(item, idx) in items" :key="item.id">
                                <button type="button"
                                        class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 flex items-center"
                                        :class="{ 'bg-gray-100': highlighted === idx }"
                                        @mouseenter="highlighted = idx"
                                        @mouseleave="highlighted = -1"
                                        @click="select(item)">
                                    <i class="fas fa-box-open text-gray-400 mr-2"></i>
                                    <span x-text="item.nom" class="flex-1"></span>
                                    <span x-text="item.categorie ? item.categorie.nom : ''" class="text-xs text-gray-500 ml-2"></span>
                                </button>
                            </template>
                            <div class="border-t">
                                <button type="button" class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-gray-50"
                                        @click="submit()">
                                    Voir tous les résultats pour « <span x-text="term"></span> »
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Cart -->
                <a href="{{ route('panier') }}" class="relative group">
                    <div class="flex items-center space-x-2 bg-blue-700 hover:bg-blue-600 px-4 py-2 rounded-lg transition">
                        <i class="fas fa-shopping-cart text-2xl"></i>
                        <div class="hidden lg:block">
                            <div class="text-xs">Mon Panier</div>
                            <div class="font-bold">
                                @auth
                                    {{ Auth::user()->panier ? Auth::user()->panier->nombreArticles() : 0 }} articles
                                @else
                                    0 articles
                                @endauth
                            </div>
                        </div>
                        @auth
                            @if(Auth::user()->panier && Auth::user()->panier->nombreArticles() > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center">
                                    {{ Auth::user()->panier->nombreArticles() }}
                                </span>
                            @endif
                        @endauth
                    </div>
                </a>
            </div>

            <!-- Mobile Search -->
            <form action="{{ route('recherche') }}" method="GET" class="md:hidden mt-4"
                  x-data="{
                    term: '{{ str_replace("'", "\'", request('q')) }}', open: false, loading: false, items: [], highlighted: -1,
                    fetch() { if (!this.term || this.term.length < 2) { this.items = []; this.open = false; return; }
                      this.loading = true; fetch('{{ route('api.suggestions') }}?term=' + encodeURIComponent(this.term))
                        .then(r => r.json()).then(d => { this.items = d; this.open = d.length > 0; this.highlighted = -1; })
                        .finally(() => this.loading = false); },
                    select(i) { const item = typeof i === 'number' ? this.items[i] : i; if (!item) { this.submit(); return; }
                      this.term = item.nom; this.$refs.input.value = item.nom; this.submit(); },
                    move(d) { if (!this.open || this.items.length === 0) return; this.highlighted = (this.highlighted + d + this.items.length) % this.items.length; },
                    submit() { this.open = false; this.$el.submit(); }
                  }">
                <div class="relative">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Rechercher..." 
                        class="w-full px-4 py-2 rounded-lg text-gray-800 focus:outline-none"
                        x-ref="input"
                        x-model="term"
                        @input.debounce.300ms="fetch()"
                        @keydown.arrow-down.prevent="move(1)"
                        @keydown.arrow-up.prevent="move(-1)"
                        @keydown.enter.prevent="highlighted >= 0 ? select(highlighted) : submit()"
                    >
                    <button type="submit" class="absolute right-2 top-2">
                        <i class="fas fa-search text-gray-600"></i>
                    </button>

                    <!-- Suggestions -->
                    <div x-cloak x-show="open" class="absolute left-0 right-0 mt-1 bg-white text-gray-800 rounded-lg shadow-2xl z-50 overflow-hidden">
                        <template x-if="loading">
                            <div class="px-4 py-2 text-sm text-gray-500">Recherche...</div>
                        </template>
                        <template x-for="(item, idx) in items" :key="item.id">
                            <button type="button"
                                    class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 flex items-center"
                                    :class="{ 'bg-gray-100': highlighted === idx }"
                                    @mouseenter="highlighted = idx"
                                    @mouseleave="highlighted = -1"
                                    @click="select(item)">
                                <i class="fas fa-box-open text-gray-400 mr-2"></i>
                                <span x-text="item.nom" class="flex-1"></span>
                                <span x-text="item.categorie ? item.categorie.nom : ''" class="text-xs text-gray-500 ml-2"></span>
                            </button>
                        </template>
                        <div class="border-t">
                            <button type="button" class="w-full text-left px-4 py-2 text-sm text-blue-600 hover:bg-gray-50" @click="submit()">
                                Voir tous les résultats pour « <span x-text="term"></span> »
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Navigation -->
        <nav class="bg-blue-700 border-t border-blue-600 relative" x-data="{ catOpen: false }" @keydown.escape.window="catOpen=false" @scroll.window="catOpen=false">
            <div class="container mx-auto px-4">
                <ul class="flex space-x-1 overflow-x-auto py-2">
                    <li>
                        <a href="{{ route('accueil') }}" class="block px-4 py-2 hover:bg-blue-600 rounded transition whitespace-nowrap">
                            <i class="fas fa-home mr-1"></i> Accueil
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('produits') }}" class="block px-4 py-2 hover:bg-blue-600 rounded transition whitespace-nowrap">
                            <i class="fas fa-th mr-1"></i> Tous les produits
                        </a>
                    </li>
                    <li class="relative">
                        <button @click="catOpen = !catOpen" class="block px-4 py-2 hover:bg-blue-600 rounded transition whitespace-nowrap">
                            <i class="fas fa-list mr-1"></i> Catégories
                            <i class="fas fa-chevron-down ml-2 text-xs"></i>
                        </button>
                        <!-- Le dropdown Catégories est déplacé en bloc sous la barre de navigation -->
                    </li>
                    
                    <li>
                        <a href="{{ route('contact') }}" class="block px-4 py-2 hover:bg-blue-600 rounded transition whitespace-nowrap">
                            <i class="fas fa-envelope mr-1"></i> Contact
                        </a>
                    </li>
                </ul>
                <!-- Bloc Catégories en overlay absolu sous la barre de navigation -->
                <div x-cloak x-show="catOpen" @click.outside="catOpen=false" class="absolute left-0 right-0 top-full bg-white text-gray-800 shadow-2xl border-t border-blue-600 z-40 max-h-[70vh] overflow-y-auto">
                    <div class="container mx-auto px-4 py-4">
                        @php $cats = $categoriesMenu ?? \App\Models\Categorie::orderBy('nom')->get(); @endphp
                        <div class="grid md:grid-cols-3 gap-3">
                            @foreach($cats as $cat)
                                <a href="{{ route('categorie.produits', ['id' => $cat->id]) }}" class="flex items-center px-3 py-2 rounded hover:bg-gray-100 transition">
                                    <i class="{{ $cat->icone }} mr-2 {{ $cat->couleur }}"></i>
                                    <span class="text-sm font-medium">{{ $cat->nom }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Backdrop overlay lorsque le bloc catégories est ouvert -->
                <div x-cloak x-show="catOpen" class="fixed inset-0 bg-black/30 z-30" @click="catOpen=false"></div>
            </div>
        </nav>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow" role="alert">
                <p class="font-bold">Succès!</p>
                <p>{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow" role="alert">
                <p class="font-bold">Erreur!</p>
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 mt-12">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">À propos</h3>
                    <p class="text-sm mb-4">Mini Amazon est votre marketplace de confiance pour tous vos achats en ligne. Livraison rapide et sécurisée.</p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Liens rapides</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('produits') }}" class="hover:text-blue-400 transition">Nos produits</a></li>
                        <li><a href="{{ route('categories') }}" class="hover:text-blue-400 transition">Catégories</a></li>
                        <li><a href="{{ route('promos') }}" class="hover:text-blue-400 transition">Promotions</a></li>
                        <li><a href="{{ route('nouveautes') }}" class="hover:text-blue-400 transition">Nouveautés</a></li>
                    </ul>
                </div>

                <!-- Customer Service -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Service client</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('aide') }}" class="hover:text-blue-400 transition">Centre d'aide</a></li>
                        <li><a href="{{ route('livraison') }}" class="hover:text-blue-400 transition">Modes de livraison</a></li>
                        <li><a href="{{ route('retours') }}" class="hover:text-blue-400 transition">Retours & Remboursements</a></li>
                        <li><a href="{{ route('cgv') }}" class="hover:text-blue-400 transition">CGV</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-white text-lg font-bold mb-4">Contact</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start">
                            <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                            <span>BENIN / PORTO-NOVO / DJEGAN-KPEVI</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2"></i>
                            <a href="tel:+2290154253797" class="hover:text-blue-400 transition" title="Appeler">
                                +229 01 54 25 37 97
                            </a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=mimiamazone555@gmail.com" target="_blank" rel="noopener noreferrer" class="hover:text-blue-400 transition" title="Écrire à l'équipe">
                                mimiamazone555@gmail.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Mini Amazon. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>