@extends('layouts.store')

@section('title', 'Accueil - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    {{-- Bandeau promo unique: s’affiche uniquement s’il y a une réduction déclarée par l’admin --}}
    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl shadow-2xl mb-12 overflow-hidden">
        <div class="grid md:grid-cols-2 gap-8 items-center p-8 md:p-12">
            <div class="text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 animate-fade-in">
                    Bienvenue sur Mini Amazon
                </h1>
                <p class="text-lg md:text-xl mb-6 text-purple-100">
                    Découvrez des milliers de produits avec livraison rapide à votre porte ou en point relais
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('produits') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition transform hover:scale-105 shadow-lg">
                        Découvrir nos produits
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                    <a href="{{ route('promos') }}" class="bg-orange-500 text-white px-8 py-3 rounded-lg font-bold hover:bg-orange-600 transition transform hover:scale-105 shadow-lg">
                        <i class="fas fa-tag mr-2"></i>
                        Voir les promos
                    </a>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="relative">
                    <div class="absolute inset-0 bg-white opacity-10 rounded-full animate-pulse"></div>
                    <i class="fas fa-shopping-cart text-white text-9xl opacity-20"></i>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($reductionActive))
    <!-- Promo Bar (unique) -->
    <section class="mb-10" x-data="{ openModal:false }">
        <div class="rounded-xl bg-blue-600 text-white px-6 py-4 shadow-lg flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700"><i class="fas fa-percent"></i></span>
                <div>
                    <div class="font-semibold">{{ $reductionActive->pourcentage }}% de réduction</div>
                    <div class="text-sm text-blue-100">
                        Du {{ optional($reductionActive->date_debut)->locale('fr_FR')->isoFormat('DD MMM YYYY') }}
                        au {{ optional($reductionActive->date_fin)->locale('fr_FR')->isoFormat('DD MMM YYYY') }}
                    </div>
                </div>
            </div>
            <div class="text-sm md:text-base inline-flex items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1">
                    <i class="fas fa-star"></i>
                    Des avantages fidélité existent
                </span>
                <button type="button" class="ml-2 underline decoration-white/50 hover:decoration-white" x-on:click="openModal = true">En savoir plus</button>
            </div>
        </div>
        <p class="mt-2 text-xs text-gray-600">Un seul avantage par commande: code promo OU réduction de période.</p>

        <!-- Modal info fidélité -->
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-show="openModal" x-cloak>
            <div class="absolute inset-0 bg-black/40" x-on:click="openModal=false"></div>
            <div class="relative bg-white rounded-xl shadow-xl w-full max-w-lg p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-lg font-bold">Programme de fidélité</h3>
                    <button class="text-gray-500 hover:text-gray-700" x-on:click="openModal=false"><i class="fas fa-times"></i></button>
                </div>
                <div class="space-y-2 text-sm text-gray-700">
                    <p>Les clients éligibles (10 achats en 12 mois) reçoivent un <strong>code promo personnel de 10%</strong>, valable <strong>2 mois</strong> et <strong>utilisable une seule fois</strong>.</p>
                    <p>Ce code est envoyé <strong>par e-mail</strong> et est <strong>réservé au compte du client concerné</strong>. Il n’est pas visible publiquement.</p>
                    <p>Règle d’exclusivité: <strong>un seul avantage par commande</strong> (code promo fidélité <em>ou</em> réduction de période).</p>
                </div>
                <div class="mt-4 flex items-center justify-end">
                    <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700" x-on:click="openModal=false">Fermer</button>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Features -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="fas fa-truck text-blue-600 text-3xl"></i>
            </div>
            <h3 class="text-center font-bold text-lg mb-2">Livraison Rapide</h3>
            <p class="text-center text-gray-600 text-sm">Livraison en 3-5 jours ouvrables</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="fas fa-shield-alt text-green-600 text-3xl"></i>
            </div>
            <h3 class="text-center font-bold text-lg mb-2">Paiement Sécurisé</h3>
            <p class="text-center text-gray-600 text-sm">Vos données sont protégées</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="fas fa-map-marker-alt text-purple-600 text-3xl"></i>
            </div>
            <h3 class="text-center font-bold text-lg mb-2">Points Relais</h3>
            <p class="text-center text-gray-600 text-sm">Retrait gratuit près de chez vous</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition transform hover:-translate-y-2">
            <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mb-4 mx-auto">
                <i class="fas fa-headset text-orange-600 text-3xl"></i>
            </div>
            <h3 class="text-center font-bold text-lg mb-2">Support 24/7</h3>
            <p class="text-center text-gray-600 text-sm">Nous sommes là pour vous aider</p>
        </div>
    </div>

    <!-- Categories -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Catégories populaires</h2>
            <a href="{{ route('categories') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
            @foreach($categories as $categorie)
            <a href="{{ route('categorie.produits', $categorie->id) }}" class="group">
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="bg-white ring-1 ring-gray-200 w-20 h-20 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition">
                        <i class="{{ $categorie->icone }} {{ $categorie->couleur }} text-3xl"></i>
                    </div>
                    <h3 class="text-center font-semibold text-base group-hover:text-blue-600 transition">
                        {{ $categorie->nom }}
                    </h3>
                    
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Produits en vedette -->
    <div class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Produits en vedette</h2>
            <a href="{{ route('produits') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Voir tout <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($produits as $produit)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-2">
                <div class="relative">
                    <img src="{{ $produit->image_url }}" 
                         alt="{{ $produit->nom }}" 
                         class="w-full h-64 object-cover">
                    @if($produit->stock < 10)
                    <span class="absolute top-4 right-4 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                        Stock limité
                    </span>
                    @endif
                </div>
                
                <div class="p-4">
                    <a href="{{ route('produit.details', $produit->id) }}" class="text-gray-600 text-xs hover:text-blue-600">
                        {{ $produit->categorie->nom }}
                    </a>
                    
                    <h3 class="font-bold text-lg mb-2 line-clamp-2">
                        <a href="{{ route('produit.details', $produit->id) }}" class="hover:text-blue-600 transition">
                            {{ $produit->nom }}
                        </a>
                    </h3>
                    
                    <div class="flex items-center mb-3">
                        @php
                            $moyenne = $produit->moyenneNotes();
                            $nombreAvis = $produit->nombreAvis();
                        @endphp
                        <div class="flex text-yellow-400 mr-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($moyenne))
                                    <i class="fas fa-star"></i>
                                @elseif($i == ceil($moyenne) && $moyenne - floor($moyenne) >= 0.5)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span class="text-gray-600 text-sm">({{ $nombreAvis }})</span>
                    </div>
                    
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-2xl font-bold text-blue-600">@fcfa($produit->prix)</span>
                        @if($produit->stock > 0)
                            <span class="text-green-600 text-sm font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>En stock
                            </span>
                        @else
                            <span class="text-red-600 text-sm font-semibold">
                                <i class="fas fa-times-circle mr-1"></i>Rupture
                            </span>
                        @endif
                    </div>
                    
                    <form action="{{ route('panier.ajouter') }}" method="POST">
                        @csrf
                        <input type="hidden" name="produit_id" value="{{ $produit->id }}">
                        <button type="submit" 
                                class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                                {{ $produit->stock == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus mr-2"></i>
                            Ajouter au panier
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    

    <!-- Newsletter -->
    <div class="bg-white rounded-2xl shadow-lg p-8 md:p-12 mb-12">
        <div class="max-w-3xl mx-auto text-center">
            <i class="fas fa-envelope text-blue-600 text-5xl mb-4"></i>
            <h2 class="text-3xl font-bold mb-4">Inscrivez-vous à notre newsletter</h2>
            <p class="text-gray-600 mb-6">
                Recevez les dernières offres et nouveautés directement dans votre boîte mail
            </p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                @csrf
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Votre adresse email" 
                    class="flex-1 px-6 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition transform hover:scale-105">
                    S'inscrire
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 1s ease-out;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection