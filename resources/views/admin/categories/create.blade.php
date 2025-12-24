@extends('layouts.admin')

@section('title', 'Admin - Nouvelle catégorie')

@section('admin-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-6">Nouvelle catégorie</h1>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white p-6 rounded-xl shadow max-w-2xl">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium">Nom</label>
                <input type="text" name="nom" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="w-full border rounded px-3 py-2" rows="4"></textarea>
            </div>
        </div>
        <div class="mt-6">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
            <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600">Annuler</a>
        </div>
    </form>
</div>
@endsection
