@extends('livreur.layouts.livreur')

@section('title', 'Mon profil')

@section('livreur-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-6">Mon profil</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold mb-4">Mes informations</h2>
            <div class="text-sm space-y-2">
                <div><span class="text-gray-500">Nom:</span> {{ $livreur->nom }} {{ $livreur->prenom }}</div>
                <div><span class="text-gray-500">Email:</span> {{ $livreur->email }}</div>
                <div><span class="text-gray-500">Téléphone:</span> {{ $livreur->telephone }}</div>
                <div><span class="text-gray-500">Ville:</span> {{ $livreur->ville }}</div>
                @if($livreur->quartier)
                    <div><span class="text-gray-500">Quartier:</span> {{ $livreur->quartier }}</div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="font-bold mb-4">Changer mon mot de passe</h2>
            <form method="POST" action="{{ route('livreur.password.update') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mot de passe actuel</label>
                    <input type="password" name="current_password" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-300">
                    @error('current_password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                    <input type="password" name="password" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-300">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" required class="mt-1 w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-300">
                    @error('password_confirmation')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg px-4 py-2">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
