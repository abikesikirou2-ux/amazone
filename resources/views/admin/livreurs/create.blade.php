@extends('layouts.admin')

@section('title', 'Admin - Ajouter un livreur')

@section('admin-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-6">Ajouter un livreur</h1>

    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('admin.livreurs.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prénom</label>
                    <input type="text" name="prenom" value="{{ old('prenom') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ville</label>
                    <input type="text" name="ville" value="{{ old('ville') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quartier</label>
                    <input type="text" name="quartier" value="{{ old('quartier') }}" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mot de passe initial (optionnel)</label>
                    <input type="text" name="password" value="{{ old('password') }}" placeholder="Laisser vide pour générer automatiquement" class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-300">
                </div>
                <div class="md:col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="disponible" value="1" class="rounded mr-2" {{ old('disponible', true) ? 'checked' : '' }}>
                        Disponible
                    </label>
                </div>
            </div>

        
            <div class="flex items-center justify-end gap-2 pt-4">
                <a href="{{ route('admin.livreurs.index') }}" class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200">Annuler</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
@endsection
