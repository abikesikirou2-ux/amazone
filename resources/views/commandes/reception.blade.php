@extends('layouts.store')

@section('title', 'Confirmation de réception')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow p-6 mt-8 text-center">
        @if(!($ok ?? false))
            <div class="text-red-600 text-2xl mb-2"><i class="fas fa-times-circle"></i></div>
            <h1 class="text-xl font-bold mb-2">Lien invalide</h1>
            <p class="text-gray-600">Le code de confirmation n'est pas valide ou a expiré.</p>
        @else
            <div class="text-emerald-600 text-2xl mb-2"><i class="fas fa-check-circle"></i></div>
            <h1 class="text-xl font-bold mb-2">Commande reçue</h1>
            @if(!empty($deja))
                <p class="text-gray-600">Cette commande a déjà été confirmée comme livrée.</p>
            @else
                <p class="text-gray-600">Merci, la réception de votre commande {{ $commande->numero_commande ?? '' }} a été confirmée.</p>
            @endif
            <a href="{{ route('accueil') }}" class="inline-block mt-4 px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Retour à l'accueil</a>
        @endif
    </div>
    <div class="h-8"></div>
</div>
@endsection
