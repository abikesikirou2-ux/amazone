@extends('layouts.store')

@section('title', 'Produits - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Filtres -->
        <div class="lg:w-1/4">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-6">Filtres</h2>

                <!-- Catégories -->
                <div class="mb-6">
                    <h3 class="font-bold mb-3">Catégories</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('produits') }}" class="text-gray-700 hover:text-blue-600 {{ !request('categorie') ? 'font-bold text-blue-600' : '' }}">
                                <i class="fas fa-th mr-2"></i> Toutes les catégories
                            </a>
                        </li>
                        @foreach($categories as $categorie)
                        <li>
                            <a href="{{ route('produits', ['categorie' => $categorie->id]) }}" 
                               class="text-gray-700 hover:text-blue-600 {{ (int)request('categorie') === $categorie->id ? 'font-bold text-blue-600' : '' }}">
                                <i class="{{ $categorie->icone }} mr-2 {{ $categorie->couleur }}"></i> {{ $categorie->nom }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Tri -->
                <div>
                    <h3 class="font-bold mb-3">Trier par</h3>
                    <form action="{{ route('produits') }}" method="GET">
                        @if(request('categorie'))
                            <input type="hidden" name="categorie" value="{{ request('categorie') }}">
                        @endif
                        @if(request('q'))
                            <input type="hidden" name="q" value="{{ request('q') }}">
                        @endif
                        
                        <select name="sort" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Plus récents</option>
                            <option value="prix_asc" {{ request('sort') == 'prix_asc' ? 'selected' : '' }}>Prix croissant</option>
                            <option value="prix_desc" {{ request('sort') == 'prix_desc' ? 'selected' : '' }}>Prix décroissant</option>
                            <option value="nom" {{ request('sort') == 'nom' ? 'selected' : '' }}>Nom A-Z</option>
                        </select>
                    </form>
                </div>

                @php
                    $catId = request('categorie') ?? request()->route('id');
                    $cat = $catId ? $categories->firstWhere('id', (int)$catId) : null;
                @endphp
                @if($cat && \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($cat->nom), 'mode'))
                <div class="mt-6">
                    <h3 class="font-bold mb-3">Segment</h3>
                    <form action="{{ route('produits') }}" method="GET" class="space-y-2">
                        <input type="hidden" name="categorie" value="{{ $catId }}">
                        @php
                            $seg = request('segment');
                        @endphp
                        <select name="segment" onchange="this.form.submit()" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <option value="">Tous</option>
                            <option value="femme" {{ $seg==='femme' ? 'selected' : '' }}>Femme</option>
                            <option value="homme" {{ $seg==='homme' ? 'selected' : '' }}>Homme</option>
                            <option value="enfant" {{ $seg==='enfant' ? 'selected' : '' }}>Enfant</option>
                        </select>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="lg:w-3/4">
            @if(request('q'))
                <div class="mb-6">
                    <h1 class="text-2xl font-bold">
                        Résultats pour "{{ request('q') }}"
                        <span class="text-gray-600 font-normal">({{ $produits->total() }} produits)</span>
                    </h1>
                </div>
            @elseif(request('categorie') || request()->route('id'))
                <div class="mb-6">
                    <h1 class="text-2xl font-bold">
                        @php
                            $__id = request('categorie') ?? request()->route('id');
                        @endphp
                        {{ $categories->firstWhere('id', (int)$__id)->nom ?? 'Produits' }}
                        <span class="text-gray-600 font-normal">({{ $produits->total() }} produits)</span>
                    </h1>
                </div>
            @endif

            @if($produits->isEmpty())
                <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                    <i class="fas fa-search text-gray-300 text-6xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-700 mb-4">Aucun produit trouvé</h2>
                    <p class="text-gray-600 mb-6">Essayez de modifier vos critères de recherche</p>
                    <a href="{{ route('produits') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                        Voir tous les produits
                    </a>
                </div>
            @else
                <!-- Grille de produits -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
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

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $produits->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection