@extends('layouts.admin')

@section('title', 'Admin - Livreurs')

@section('admin-content')
<div class="px-0">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Livreurs</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.livreurs.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm shadow">
                <i class="fas fa-plus mr-2"></i> Ajouter un livreur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Nom</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Téléphone</th>
                    <th class="px-4 py-2 text-left">Ville</th>
                    <th class="px-4 py-2 text-left">Quartier</th>
                    <th class="px-4 py-2 text-center">Validé</th>
                    <th class="px-4 py-2 text-center">Disponible</th>
                    <th class="px-4 py-2 text-left">Dernier envoi validation</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($livreurs as $l)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $l->nom }} {{ $l->prenom }}</td>
                    <td class="px-4 py-2">{{ $l->email }}</td>
                    <td class="px-4 py-2">{{ $l->telephone }}</td>
                    <td class="px-4 py-2">{{ $l->ville }}</td>
                    <td class="px-4 py-2">{{ $l->quartier }}</td>
                    <td class="px-4 py-2 text-center">
                        @if($l->valide)
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-green-100 text-green-700">
                                <i class="fas fa-check mr-1"></i> Oui
                            </span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-700">
                                <i class="fas fa-clock mr-1"></i> En attente
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs {{ $l->disponible ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            <i class="fas {{ $l->disponible ? 'fa-check' : 'fa-minus' }} mr-1"></i>
                            {{ $l->disponible ? 'Oui' : 'Non' }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        @if($l->validation_envoye_le)
                            <span title="{{ $l->validation_envoye_le }}">{{ $l->validation_envoye_le->diffForHumans() }}</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <!-- Basculer disponibilité -->
                            <form method="POST" action="{{ route('admin.livreurs.toggle', $l->id) }}">
                                @csrf
                                <button title="Basculer disponibilité" aria-label="Basculer disponibilité" class="inline-flex items-center justify-center w-9 h-9 rounded hover:bg-gray-100 text-gray-700">
                                    <i class="fas {{ $l->disponible ? 'fa-toggle-on' : 'fa-toggle-off' }} text-lg"></i>
                                </button>
                            </form>

                            @if(!$l->valide)
                                <!-- Renvoyer validation -->
                                <form method="POST" action="{{ route('admin.livreurs.renvoyer', $l->id) }}" onsubmit="return confirm('Renvoyer l\'email de validation à {{ $l->email }} ?');">
                                    @csrf
                                    <button title="Renvoyer validation" aria-label="Renvoyer validation" class="inline-flex items-center justify-center w-9 h-9 rounded hover:bg-gray-100 text-gray-700">
                                        <i class="fas fa-paper-plane text-sm"></i>
                                    </button>
                                </form>
                                <!-- Forcer envoi -->
                                <form method="POST" action="{{ route('admin.livreurs.renvoyer.force', $l->id) }}" onsubmit="return confirm('Forcer l\'envoi de l\'email de validation à {{ $l->email }} ?');">
                                    @csrf
                                    <button title="Forcer envoi" aria-label="Forcer envoi" class="inline-flex items-center justify-center w-9 h-9 rounded hover:bg-gray-100 text-gray-700">
                                        <i class="fas fa-bolt text-sm"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Supprimer -->
                            <form method="POST" action="{{ route('admin.livreurs.destroy', $l->id) }}" onsubmit="return confirm('Supprimer le livreur {{ $l->email }} ?');">
                                @csrf
                                @method('DELETE')
                                <button title="Supprimer" aria-label="Supprimer" class="inline-flex items-center justify-center w-9 h-9 rounded hover:bg-gray-100 text-gray-700">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td class="p-4 text-center text-gray-500" colspan="9">Aucun livreur</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $livreurs->links() }}
    </div>
</div>
@endsection
