@extends('layouts.admin')

@section('title', 'Admin - Produits')

@section('admin-content')
<div class="px-0">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Produits</h1>
        <a href="{{ route('admin.produits.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Nouveau produit
        </a>
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
                    <th class="px-4 py-2 text-left">Catégorie</th>
                    <th class="px-4 py-2 text-right">Prix</th>
                    <th class="px-4 py-2 text-right">Stock</th>
                    <th class="px-4 py-2 text-center">Actif</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($produits as $p)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $p->nom }}</td>
                    <td class="px-4 py-2">{{ $p->categorie->nom ?? '-' }}</td>
                    <td class="px-4 py-2 text-right">@fcfa($p->prix)</td>
                    <td class="px-4 py-2 text-right">{{ $p->stock }}</td>
                    <td class="px-4 py-2 text-center">
                        <form method="POST" action="{{ route('admin.produits.toggleActif', $p->id) }}">
                            @csrf
                            <button class="inline-flex items-center px-3 py-1 rounded {{ $p->actif ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                <i class="fas {{ $p->actif ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-2"></i>
                                {{ $p->actif ? 'Actif' : 'Inactif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.produits.edit', $p->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.produits.stock', $p->id) }}" class="inline-flex items-center gap-2">
                            @csrf
                            <select name="type" class="border rounded px-2 py-1 text-xs">
                                <option value="ajout">+ Stock</option>
                                <option value="retrait">- Stock</option>
                            </select>
                            <input type="number" name="quantite" min="1" class="border rounded px-2 py-1 w-20 text-xs" placeholder="Qté">
                            <input type="text" name="notes" class="border rounded px-2 py-1 w-36 text-xs" placeholder="Notes">
                            <button class="bg-orange-500 text-white px-3 py-1 rounded text-xs">OK</button>
                        </form>
                        <form method="POST" action="{{ route('admin.produits.destroy', $p->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800" onclick="return confirm('Supprimer ce produit ?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $produits->links() }}
    </div>
</div>
@endsection
