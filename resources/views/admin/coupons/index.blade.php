@extends('layouts.admin')

@section('title', 'Coupons clients - Mini Amazon')

@section('admin-content')
<div class="max-w-6xl">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Coupons attribués aux clients</h1>
    </div>

    @if(session('success'))
        <div class="mb-4 rounded border border-green-200 bg-green-50 p-3 text-green-800">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-red-800">{{ session('error') }}</div>
    @endif

    <div class="overflow-auto bg-white shadow rounded-xl">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Code</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Client</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Type</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Origine</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Valeur</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Début</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Fin</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Utilisation</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Statut</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
            @forelse($coupons as $c)
                <tr>
                    <td class="px-4 py-3 font-semibold">{{ $c->code }}</td>
                    <td class="px-4 py-3">
                        @if($c->user)
                            <div class="font-medium">{{ $c->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $c->user->email }}</div>
                        @else
                            <span class="text-xs text-gray-500">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ ucfirst($c->type) }}</td>
                    <td class="px-4 py-3">
                        @php($isFidelite = !empty($c->user_id) && $c->type==='pourcentage' && (int)$c->valeur===10 && (int)($c->utilisations_max ?? 0)===1)
                        @if($isFidelite)
                            <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700"><i class="fas fa-star"></i> Fidélité (10 achats)</span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">Autre</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">@if($c->type==='pourcentage') -{{ $c->valeur }}% @else @fcfa($c->valeur) @endif</td>
                    <td class="px-4 py-3">{{ optional($c->date_debut)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ optional($c->date_fin)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 text-sm">{{ $c->compteur_utilisation }} / {{ $c->utilisations_max ?? '∞' }}</td>
                    <td class="px-4 py-3">
                        @if($c->actif)
                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700"><i class="fas fa-check"></i> Actif</span>
                        @else
                            <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700"><i class="fas fa-ban"></i> Inactif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.coupons.toggle', $c->id) }}" class="inline">
                            @csrf
                            <button class="px-3 py-2 rounded text-sm font-semibold {{ $c->actif ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}">
                                {{ $c->actif ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">Aucun coupon attribué pour le moment.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $coupons->links() }}</div>
</div>
@endsection
