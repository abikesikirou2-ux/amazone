@extends('layouts.admin')

@section('title', 'Admin - Tableau de bord')

@section('admin-content')
<div class="px-0">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-xl shadow p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-sm text-blue-100">Administration</div>
                <h1 class="text-3xl font-bold">Tableau de bord</h1>
                <div class="text-sm text-blue-100 mt-1">Vue d'ensemble des ventes et activités</div>
            </div>
            <div class="hidden md:flex items-center">
                <span class="inline-flex h-12 w-12 items-center justify-center rounded-lg bg-blue-700">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </span>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">{{ session('error') }}</div>
    @endif
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Total des ventes</div>
                    <div class="text-2xl font-bold">@fcfa($totalVentes)</div>
                </div>
                <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">
                    <i class="fas fa-euro-sign"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Nombre de commandes</div>
                    <div class="text-2xl font-bold">{{ $nbCommandes }}</div>
                </div>
                <div class="h-10 w-10 rounded-lg bg-emerald-50 text-emerald-600 grid place-items-center">
                    <i class="fas fa-shopping-basket"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Panier moyen</div>
                    <div class="text-2xl font-bold">@fcfa($panierMoyen)</div>
                </div>
                <div class="h-10 w-10 rounded-lg bg-amber-50 text-amber-600 grid place-items-center">
                    <i class="fas fa-balance-scale"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-gray-500 text-sm">Ventes aujourd'hui</div>
                    <div class="text-2xl font-bold">@fcfa($ventesAujourdHui)</div>
                </div>
                <div class="h-10 w-10 rounded-lg bg-purple-50 text-purple-600 grid place-items-center">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
        <!-- Sparkline 7 jours -->
        <div class="bg-white rounded-xl shadow p-5 xl:col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-lg">Statistiques de vente (7 jours)</h2>
                <div class="text-xs text-gray-500">Derniers 7 jours</div>
            </div>
            @php
                $max = ($serie7j->max('total') ?: 0);
                $w = 560; $h = 120; $pad = 10;
                $n = max(count($serie7j), 1);
                $step = $n > 1 ? ($w - 2*$pad) / ($n - 1) : 0;
                $points = [];
                foreach($serie7j as $i => $p){
                    $x = $pad + $i * $step;
                    $y = $h - $pad - ($max > 0 ? ($p['total'] / $max) * ($h - 2*$pad) : 0);
                    $points[] = $x.','.$y;
                }
            @endphp
            <div class="overflow-x-auto">
                <svg viewBox="0 0 {{ $w }} {{ $h }}" class="w-full h-40">
                    <rect x="0" y="0" width="{{ $w }}" height="{{ $h }}" fill="#fff" />
                    <polyline fill="none" stroke="#3b82f6" stroke-width="3" points="{{ implode(' ', $points) }}" />
                    @foreach($serie7j as $i => $p)
                        @php
                            $x = $pad + $i * $step;
                            $y = $h - $pad - ($max > 0 ? ($p['total'] / $max) * ($h - 2*$pad) : 0);
                        @endphp
                        <circle cx="{{ $x }}" cy="{{ $y }}" r="3" fill="#3b82f6" />
                    @endforeach
                </svg>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                @foreach($serie7j as $p)
                    <span>{{ $p['label'] }}</span>
                @endforeach
            </div>
        </div>

        <!-- Répartition statuts -->
        <div class="bg-white rounded-xl shadow p-5">
            <h2 class="font-bold text-lg mb-3">Répartition par statut</h2>
            <div class="flex flex-wrap gap-2">
                @forelse($parStatut as $s => $c)
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">{{ ucfirst(str_replace('_',' ', $s)) }}
                        <span class="ml-2 font-semibold">{{ $c }}</span>
                    </span>
                @empty
                    <span class="text-gray-500 text-sm">Aucune commande</span>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="bg-white rounded-xl shadow p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-bold text-lg">Liste des ventes (10 dernières)</h2>
            <a href="{{ route('admin.commandes.index') }}" class="text-blue-600 text-sm hover:underline">Voir toutes les commandes</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Date</th>
                        <th class="px-4 py-2 text-left">Numéro</th>
                        <th class="px-4 py-2 text-left">Client</th>
                        <th class="px-4 py-2 text-left">Statut</th>
                        <th class="px-4 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dernieresCommandes as $c)
                        <tr class="border-t">
                            <td class="px-4 py-2 whitespace-nowrap">{{ optional($c->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">{{ $c->numero_commande }}</td>
                            <td class="px-4 py-2">{{ $c->user?->name }} <span class="text-xs text-gray-500">{{ $c->user?->email }}</span></td>
                            <td class="px-4 py-2">{{ $c->statut ?? '-' }}</td>
                            <td class="px-4 py-2 text-right">@fcfa($c->total)</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Aucune commande récente</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Accès rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        <a href="{{ route('admin.produits.index') }}" class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <h2 class="font-bold text-xl mb-2"><i class="fas fa-box mr-2"></i>Produits</h2>
            <p class="text-blue-100">Gérer le catalogue, le stock et l'activation.</p>
        </a>
        <a href="{{ route('admin.categories.index') }}" class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <h2 class="font-bold text-xl mb-2"><i class="fas fa-list mr-2"></i>Catégories</h2>
            <p class="text-blue-100">Créer, modifier et supprimer des catégories.</p>
        </a>
        <a href="{{ route('admin.livreurs.index') }}" class="p-6 rounded-xl shadow hover:shadow-lg transition bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <h2 class="font-bold text-xl mb-2"><i class="fas fa-motorcycle mr-2"></i>Livreurs</h2>
            <p class="text-blue-100">Disponibilité et répartition.</p>
        </a>
    </div>
</div>
@endsection
