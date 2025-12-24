@extends('livreur.layouts.livreur')

@section('title', 'Mes livraisons')

@section('livreur-content')
<div class="px-0">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-blue-100">Espace Livreur</div>
                <h1 class="text-2xl font-bold">Mes livraisons</h1>
                <div class="text-sm text-blue-100 mt-1">Total: {{ $totalLivraisons }}</div>
            </div>
            <div class="hidden md:flex items-center">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-700">
                    <i class="fas fa-motorcycle text-white text-xl"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Filtres par statut -->
    <div class="bg-white rounded-xl shadow p-3 mb-4">
        @php
            $statuts = ['en_cours' => 'En cours', 'livree' => 'Livrée'];
            $isActive = function($s) use ($filtre) { return $filtre === $s ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100'; };
            $badgeClass = function($s){ return $s === 'livree' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700'; };
        @endphp
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('livreur.dashboard') }}" class="px-3 py-1.5 rounded border {{ $filtre ? 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">Toutes
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-700">{{ array_sum($compteParStatut?->toArray() ?? []) }}</span>
            </a>
            @foreach($statuts as $code => $label)
                <a href="{{ route('livreur.dashboard', ['statut' => $code]) }}" class="px-3 py-1.5 rounded border {{ $isActive($code) }}">
                    {{ $label }}
                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs {{ $badgeClass($code) }}">{{ $compteParStatut[$code] ?? 0 }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Date</th>
                    <th class="px-4 py-2 text-left">Commande</th>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Adresse / Point relais</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2 text-right">Total</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($commandes as $c)
                <tr class="border-t">
                    <td class="px-4 py-2 whitespace-nowrap">{{ optional($c->created_at)->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-2">{{ $c->numero_commande }}</td>
                    <td class="px-4 py-2">{{ $c->user?->name }} <span class="text-xs text-gray-500">{{ $c->user?->email }}</span></td>
                    <td class="px-4 py-2">
                        @if($c->pointRelais)
                            Point relais: {{ $c->pointRelais->nom }} ({{ $c->pointRelais->ville }})
                        @else
                            {{ $c->adresse_livraison }}, {{ $c->quartier_livraison }} - {{ $c->ville_livraison }}
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @php
                            $s = $c->statut ?? 'inconnu';
                            $cls = match($s){
                                'livree' => 'bg-green-100 text-green-700',
                                'en_cours' => 'bg-blue-100 text-blue-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                            $label = $s === 'livree' ? 'Livrée' : ($s === 'en_cours' ? 'En cours' : ucfirst(str_replace('_',' ', $s)));
                        @endphp
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $cls }}">{{ $label }}</span>
                        @if($c->recu_le)
                            <span class="inline-flex items-center px-2 py-1 rounded text-[10px] ml-2 bg-emerald-50 text-emerald-700">
                                <i class="fas fa-qrcode mr-1"></i> QR scanné
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-right">@fcfa($c->total)</td>
                    <td class="px-4 py-2 text-right whitespace-nowrap">
                        @php
                            $destination = $c->pointRelais
                                ? ($c->pointRelais->nom . ', ' . $c->pointRelais->ville)
                                : trim(($c->adresse_livraison ?? '') . ', ' . ($c->quartier_livraison ?? '') . ' - ' . ($c->ville_livraison ?? ''));
                            $mapsUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($destination) . '&travelmode=driving';
                        @endphp
                        <a href="{{ route('livreur.commande.qr', $c->id) }}" class="inline-flex items-center px-3 py-1.5 rounded bg-blue-600 text-white text-xs hover:bg-blue-700 mr-2">
                            <i class="fas fa-qrcode mr-1"></i> QR code
                        </a>
                        <a href="{{ $mapsUrl }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded bg-blue-600 text-white text-xs hover:bg-blue-700">
                            <i class="fas fa-route mr-1"></i> Itinéraire
                        </a>
                        @if(($c->statut ?? 'en_cours') !== 'livree')
                            @if($c->recu_le)
                                <form method="POST" action="{{ route('livreur.commande.livrer', $c->id) }}" class="inline" onsubmit="return confirm('Confirmer la livraison de cette commande ?');">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 rounded bg-blue-700 text-white text-xs hover:bg-blue-800 ml-2">
                                        <i class="fas fa-check mr-1"></i> Marquer livrée
                                    </button>
                                </form>
                            @else
                                <button type="button" disabled class="inline-flex items-center px-3 py-1.5 rounded bg-gray-300 text-white text-xs ml-2 cursor-not-allowed" title="Scanner d'abord le QR avec le client">
                                    <i class="fas fa-lock mr-1"></i> Marquer livrée
                                </button>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Aucune livraison</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $commandes->links() }}</div>
</div>
@endsection
