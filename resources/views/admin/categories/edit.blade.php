@extends('layouts.admin')

@section('title', 'Admin - Modifier catégorie')

@section('admin-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-6">Modifier: {{ $categorie->nom }}</h1>

    <form method="POST" action="{{ route('admin.categories.update', $categorie->id) }}" class="bg-white p-6 rounded-xl shadow max-w-2xl">
        @csrf @method('PATCH')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nom</label>
                <input type="text" name="nom" class="w-full border rounded px-3 py-2" value="{{ old('nom', $categorie->nom) }}" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4">{{ old('description', $categorie->description) }}</textarea>
            </div>
        </div>
        <div class="mt-6">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Enregistrer</button>
            <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
