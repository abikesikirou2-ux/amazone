@extends('layouts.admin')

@section('title', 'Admin - Modifier produit')

@section('admin-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-6">Modifier: {{ $produit->nom }}</h1>

    <form method="POST" action="{{ route('admin.produits.update', $produit->id) }}" class="bg-white p-6 rounded-xl shadow max-w-2xl">
        @csrf @method('PATCH')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nom</label>
                <input type="text" name="nom" class="w-full border rounded px-3 py-2" value="{{ old('nom', $produit->nom) }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">Prix (€)</label>
                <input type="number" step="0.01" min="0" name="prix" class="w-full border rounded px-3 py-2" value="{{ old('prix', $produit->prix) }}" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4">{{ old('description', $produit->description) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium">Catégorie</label>
                <select name="categorie_id" class="w-full border rounded px-3 py-2" required>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ $c->id == $produit->categorie_id ? 'selected' : '' }}>{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Segment (Mode)</label>
                <select name="segment" class="w-full border rounded px-3 py-2">
                    <option value="" {{ $produit->segment ? '' : 'selected' }}>(aucun)</option>
                    <option value="femme" {{ $produit->segment==='femme' ? 'selected' : '' }}>Femme</option>
                    <option value="homme" {{ $produit->segment==='homme' ? 'selected' : '' }}>Homme</option>
                    <option value="enfant" {{ $produit->segment==='enfant' ? 'selected' : '' }}>Enfant</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium">Stock</label>
                <input type="number" min="0" name="stock" class="w-full border rounded px-3 py-2" value="{{ old('stock', $produit->stock) }}" required>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="actif" id="actif" {{ $produit->actif ? 'checked' : '' }}>
                <label for="actif">Actif</label>
            </div>
        </div>
        <div class="mt-6">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            <a href="{{ route('admin.produits.index') }}" class="ml-2 text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
