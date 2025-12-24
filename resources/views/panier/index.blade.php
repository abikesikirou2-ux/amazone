@extends('layouts.store')

@section('title', 'Mon Panier - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-8">Mon Panier</h1>

    @if($articles->isEmpty())
        <div class="bg-white rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-shopping-cart text-gray-300 text-6xl mb-4"></i>
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Votre panier est vide</h2>
            <p class="text-gray-600 mb-6">Découvrez nos produits et ajoutez-les à votre panier</p>
            <a href="{{ route('produits') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition">
                Continuer mes achats
            </a>
        </div>
    @else
        <div class="grid lg:grid-cols-4 gap-8">
            <aside class="lg:col-span-1">
                @include('partials.client-sidebar')
            </aside>
            <!-- Liste des articles -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($articles as $article)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex gap-6">
                        <img src="https://via.placeholder.com/150x150/667eea/ffffff?text={{ urlencode(substr($article->produit->nom, 0, 1)) }}" 
                             alt="{{ $article->produit->nom }}" 
                             class="w-32 h-32 object-cover rounded-lg">
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="font-bold text-lg">
                                        <a href="{{ route('produit.details', $article->produit->id) }}" class="hover:text-blue-600">
                                            {{ $article->produit->nom }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 text-sm">{{ $article->produit->categorie->nom }}</p>
                                </div>
                                <form action="{{ route('panier.supprimer', $article->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>

                            <p class="text-2xl font-bold text-blue-600 mb-4">@fcfa($article->prix) @if($article->taille)<span class="text-sm text-gray-500">(Taille {{ $article->taille }})</span>@endif</p>

                            <div class="flex items-center gap-4">
                                <form action="{{ route('panier.update', $article->id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="text-sm font-semibold">Quantité:</label>
                                    <input type="number" 
                                           name="quantite" 
                                           value="{{ $article->quantite }}" 
                                           min="1" 
                                           max="{{ $article->produit->stock }}"
                                           class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm">
                                        Mettre à jour
                                    </button>
                                </form>

                                <div class="text-sm text-gray-600">
                                    Sous-total: <span class="font-bold">@fcfa($article->getSousTotal())</span>
                                </div>
                            </div>

                            @if($article->produit->stock < 5)
                                <p class="text-red-600 text-sm mt-2">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Plus que {{ $article->produit->stock }} en stock
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                <form action="{{ route('panier.vider') }}" method="POST" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold" 
                            onclick="return confirm('Êtes-vous sûr de vouloir vider votre panier?')">
                        <i class="fas fa-trash mr-2"></i>
                        Vider le panier
                    </button>
                </form>
            </div>

            <!-- Résumé de la commande -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6">Résumé de la commande</h2>

                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Sous-total ({{ $articles->sum('quantite') }} articles)</span>
                            <span class="font-semibold">@fcfa($total)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Livraison</span>
                            <span class="text-sm text-gray-500">Calculé à l'étape suivante</span>
                        </div>
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total estimé</span>
                                <span class="text-blue-600">@fcfa($total)</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('commande.creer') }}" class="block w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white text-center py-4 rounded-lg font-bold hover:from-blue-700 hover:to-purple-700 transition transform hover:scale-105 mb-3">
                        <i class="fas fa-lock mr-2"></i>
                        Passer la commande
                    </a>

                    <a href="{{ route('produits') }}" class="block w-full bg-gray-200 text-gray-700 text-center py-3 rounded-lg font-semibold hover:bg-gray-300 transition">
                        Continuer mes achats
                    </a>

                    <!-- Codes promo -->
                    <div class="mt-6 pt-6 border-t">
                        <h3 class="font-bold mb-3">Code promo</h3>
                        <p class="text-sm text-gray-600 mb-2">Vous pourrez l'appliquer à l'étape suivante</p>
                        <div class="bg-blue-50 p-3 rounded-lg text-sm">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <span class="text-gray-700">Saisissez votre code personnel reçu par e‑mail (le cas échéant).</span>
                        </div>
                    </div>

                    <!-- Sécurité -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-shield-alt text-green-600 text-xl mr-3"></i>
                            <span>Paiement 100% sécurisé</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection