@extends('layouts.admin')

@section('title', 'Points relais - Admin')

@section('admin-content')
<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-bold">Points relais</h1>
    <form method="POST" action="{{ route('admin.points-relais.geocoder') }}">
        @csrf
        <button class="inline-flex items-center px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
            <i class="fas fa-map-marked-alt mr-2"></i>
            Géocoder les coordonnées manquantes
        </button>
    </form>
    </div>
    <div class="text-sm text-gray-600 mb-4">Coordonnées manquantes: {{ $manquants }}</div>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-600">
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Adresse</th>
                    <th class="px-4 py-2 text-left">Ville</th>
                    <th class="px-4 py-2 text-left">Code postal</th>
                    <th class="px-4 py-2 text-left">Latitude</th>
                    <th class="px-4 py-2 text-left">Longitude</th>
                    <th class="px-4 py-2 text-left">Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($points as $p)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ $p->nom }}</td>
                        <td class="px-4 py-2">{{ $p->adresse }}</td>
                        <td class="px-4 py-2">{{ $p->ville }}</td>
                        <td class="px-4 py-2">{{ $p->code_postal }}</td>
                        <td class="px-4 py-2">{{ $p->latitude }}</td>
                        <td class="px-4 py-2">{{ $p->longitude }}</td>
                        <td class="px-4 py-2">
                            @if($p->latitude && $p->longitude)
                                <span class="inline-flex items-center px-2 py-1 rounded bg-emerald-50 text-emerald-700"><i class="fas fa-check mr-1"></i> Ok</span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded bg-amber-50 text-amber-700"><i class="fas fa-exclamation-triangle mr-1"></i> Manquant</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucun point relais</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">{{ $points->links() }}</div>
    </div>
@endsection
