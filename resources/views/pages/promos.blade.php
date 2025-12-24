@extends('layouts.store')
@section('title', 'Promotions - Mini Amazon')
@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-4">Promotions</h1>
    @php($promo = \App\Models\ReductionGlobale::active()->first())
    @if($promo)
        <div class="rounded-xl bg-blue-600 text-white px-6 py-4 shadow mb-4">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-blue-700"><i class="fas fa-percent"></i></span>
                <div>
                    <div class="font-semibold">{{ $promo->pourcentage }}% de réduction</div>
                    <div class="text-sm text-blue-100">
                        Du {{ optional($promo->date_debut)->locale('fr_FR')->isoFormat('DD MMM YYYY') }}
                        au {{ optional($promo->date_fin)->locale('fr_FR')->isoFormat('DD MMM YYYY') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-700">La réduction de période s'applique automatiquement au panier. Les codes fidélité sont personnels et envoyés par email aux clients éligibles.</p>
            <p class="text-xs text-gray-500 mt-2">Un seul avantage par commande: code promo OU réduction de période.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow p-6">
            <p class="text-gray-700">Aucune promotion en cours. Revenez bientôt !</p>
        </div>
    @endif
</div>
@endsection
