@extends('layouts.store')

@section('title', 'Confirmation - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-10 max-w-2xl mx-auto text-center">
        <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
        <h1 class="text-3xl font-bold mb-2">Merci pour votre commande !</h1>
        <p class="text-gray-700 mb-6">Votre commande <span class="font-semibold">{{ $commande->numero_commande }}</span> a été créée avec succès.</p>
            @if($commande->coupon_id)
                <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                    <i class="fas fa-ticket-alt"></i>
                    Code promo appliqué: <span class="ml-1">{{ $commande->coupon->code }}</span>
                    @if($commande->coupon->type === 'pourcentage')
                        <span class="ml-2">(-{{ $commande->coupon->valeur }}%)</span>
                    @else
                        <span class="ml-2">(- @fcfa($commande->coupon->valeur))</span>
                    @endif
                    @if($commande->coupon->livraison_gratuite)
                        <span class="ml-2 inline-flex items-center gap-1 text-emerald-800"><i class="fas fa-truck"></i> Livraison gratuite</span>
                    @endif
                </div>
            @elseif($commande->reduction > 0)
                <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                    <i class="fas fa-badge-percent"></i>
                    Réduction de période appliquée
                </div>
            @endif
        <div class="bg-gray-50 border rounded p-4 text-left text-sm">
            <div class="flex justify-between"><span>Total</span><span class="font-bold">@fcfa($commande->total)</span></div>
            <div class="flex justify-between"><span>Statut</span><span>{{ ucfirst(str_replace('_',' ', $commande->statut)) }}</span></div>
            <div class="flex justify-between"><span>Livraison</span><span>{{ $commande->modeLivraison->nom }}</span></div>
        </div>
        <div class="mt-6 flex gap-3 justify-center">
            @if($commande->statut !== 'payee')
            <form action="{{ route('commande.payer', $commande->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700">
                    Simuler paiement et télécharger le reçu
                </button>
            </form>
            @endif
            <a href="{{ route('commande.show', $commande->id) }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">Voir les détails</a>
            <a href="{{ route('produits') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">Continuer mes achats</a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('title', 'Commande confirmée - ' . $commande->numero_commande)

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-xl shadow p-10 text-center max-w-3xl mx-auto">
        <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
        <h1 class="text-2xl font-bold mb-2">Merci pour votre commande !</h1>
        <p class="text-gray-600 mb-6">Votre numéro de commande est <span class="font-semibold">{{ $commande->numero_commande }}</span>.</p>
        <div class="text-left bg-green-50 border border-green-200 rounded-lg p-4 text-sm text-green-900 mb-6">
            <p>Nous préparons votre commande. Vous recevrez une notification dès son expédition.</p>
        </div>
        <a href="{{ route('commande.show', $commande->id) }}" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">Voir les détails</a>
        <a href="{{ route('produits') }}" class="inline-block ml-3 bg-gray-200 text-gray-800 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">Continuer mes achats</a>
    </div>
</div>
@endsection
