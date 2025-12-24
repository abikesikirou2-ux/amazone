@extends('layouts.admin')

@section('title', 'Admin - Commandes')

@section('admin-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-6">Commandes</h1>

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">#</th>
                    <th class="px-4 py-2 text-left">Numéro</th>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                    <th class="px-4 py-2 text-right">Total</th>
                    <th class="px-4 py-2 text-left">Livreur</th>
                </tr>
            </thead>
            <tbody>
            @forelse($commandes as $c)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $c->id }}</td>
                    <td class="px-4 py-2">{{ $c->numero_commande }}</td>
                    <td class="px-4 py-2">{{ $c->user?->name }} <span class="text-xs text-gray-500">{{ $c->user?->email }}</span></td>
                    <td class="px-4 py-2">{{ $c->statut ?? '-' }}</td>
                    <td class="px-4 py-2 text-right">@fcfa($c->total)</td>
                    <td class="px-4 py-2">{{ $c->livreur?->nom }} {{ $c->livreur?->prenom }}</td>
                </tr>
            @empty
                <tr><td class="p-4 text-center text-gray-500" colspan="6">Aucune commande</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $commandes->links() }}
    </div>
</div>
@endsection
