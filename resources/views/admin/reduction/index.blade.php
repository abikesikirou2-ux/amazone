@extends('layouts.admin')

@section('title', 'Admin - Réductions (Bonus)')

@section('admin-content')
<div class="px-0">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Réductions (Bonus)</h1>
        <a href="{{ route('admin.reduction.index') }}" class="text-blue-600 hover:text-blue-800">Actualiser</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Formulaire création -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4">Déclarer une période de réduction</h2>
            <form method="POST" action="{{ route('admin.reduction.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1">Date de début</label>
                    <input type="datetime-local" name="date_debut" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Date de fin</label>
                    <input type="datetime-local" name="date_fin" class="w-full border rounded px-3 py-2" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Pourcentage de réduction</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="pourcentage" min="0" max="100" step="0.01" class="flex-1 border rounded px-3 py-2" required>
                        <span class="text-gray-600">%</span>
                    </div>
                </div>
                <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" name="actif" value="1" checked> Activer</label>
                <div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
                </div>
            </form>
        </div>

        <!-- Réduction active -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4">Réduction active</h2>
            @if($active)
                <div class="p-4 rounded bg-blue-50">
                    <div class="flex items-center justify-between">
                        <div>
                            <div><span class="font-semibold">Période:</span> {{ $active->date_debut->format('d/m/Y H:i') }} → {{ $active->date_fin->format('d/m/Y H:i') }}</div>
                            <div><span class="font-semibold">Pourcentage:</span> {{ $active->pourcentage }}%</div>
                        </div>
                        <div>
                            @if($active->actif)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700"><i class="fas fa-check"></i> Actif</span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700"><i class="fas fa-pause"></i> Inactif</span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 text-sm text-blue-800">
                        @php($now = now())
                        @if($now->lt($active->date_debut))
                            Commence dans {{ $active->date_debut->locale('fr_FR')->diffForHumans($now, ['parts'=>2]) }}
                        @elseif($now->between($active->date_debut, $active->date_fin))
                            Se termine {{ $active->date_fin->locale('fr_FR')->diffForHumans($now, ['parts'=>2]) }}
                        @else
                            Terminée {{ $active->date_fin->locale('fr_FR')->diffForHumans($now, ['parts'=>2]) }}
                        @endif
                    </div>
                    @php
                        $start = $active->date_debut;
                        $end = $active->date_fin;
                        $total = max($start?->diffInSeconds($end ?? $start) ?? 1, 1);
                        $elapsed = 0;
                        if($now->gte($start) && $now->lte($end)){
                            $elapsed = $start->diffInSeconds($now);
                        } elseif($now->gt($end)) {
                            $elapsed = $total;
                        }
                        $pct = min(100, max(0, (int) floor(($elapsed / $total) * 100)));
                    @endphp
                    <div class="mt-3">
                        <div class="flex items-center justify-between mb-1 text-xs text-gray-600">
                            <span>Progression de la période</span>
                            <span>{{ $pct }}%</span>
                        </div>
                        <div class="h-2 rounded bg-gray-200 overflow-hidden">
                            <div class="h-full bg-blue-600" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.reduction.toggle', $active->id) }}" class="mt-3">
                        @csrf
                        <button class="inline-flex items-center px-3 py-1 rounded {{ $active->actif ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white">
                            {{ $active->actif ? 'Désactiver' : 'Activer' }}
                        </button>
                    </form>
                </div>
            @else
                <p class="text-gray-600">Aucune réduction active.</p>
            @endif
        </div>
    </div>

    <!-- Historique -->
    <div class="bg-white rounded-xl shadow p-6 mt-6">
        <h2 class="text-xl font-bold mb-4">Historique des périodes</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left">Début</th>
                        <th class="px-4 py-2 text-left">Fin</th>
                        <th class="px-4 py-2 text-left">Pourcentage</th>
                        <th class="px-4 py-2 text-left">Statut</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reductions as $r)
                    <tr class="border-t">
                        <td class="px-4 py-2">{{ optional($r->date_debut)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">{{ optional($r->date_fin)->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-2">{{ $r->pourcentage }}%</td>
                        <td class="px-4 py-2">
                            @if($r->actif)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700"><i class="fas fa-check"></i> Actif</span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700"><i class="fas fa-pause"></i> Inactif</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('admin.reduction.toggle', $r->id) }}">
                                @csrf
                                <button class="text-blue-600 hover:text-blue-800">{{ $r->actif ? 'Désactiver' : 'Activer' }}</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-gray-500">Aucune période</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $reductions->links() }}</div>
    </div>
</div>
@endsection
