@extends('layouts.store')

@section('title', 'Mon compte - Mini Amazon')

@section('content')
<div class="container mx-auto px-4" x-data="{ openProfileModal: false }">
    <h1 class="text-3xl font-bold mb-6">Mon compte</h1>
    @if(session('status') === 'profile-updated')
        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded">
            <p class="font-bold">Profil mis à jour</p>
            <p>Vos informations ont été enregistrées avec succès.</p>
        </div>
    @endif
    <div class="grid md:grid-cols-3 gap-6">
        <a href="{{ route('commandes') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <i class="fas fa-box text-2xl text-blue-600"></i>
                <div>
                    <div class="font-bold">Mes commandes</div>
                    <div class="text-sm text-gray-600">Historique et suivi</div>
                </div>
            </div>
        </a>
        <a href="{{ route('panier') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <i class="fas fa-shopping-cart text-2xl text-blue-600"></i>
                <div>
                    <div class="font-bold">Mon panier</div>
                    <div class="text-sm text-gray-600">Articles en attente</div>
                </div>
            </div>
        </a>
        <a href="{{ route('produits') }}" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center gap-3">
                <i class="fas fa-th text-2xl text-blue-600"></i>
                <div>
                    <div class="font-bold">Catalogue</div>
                    <div class="text-sm text-gray-600">Continuer mes achats</div>
                </div>
            </div>
        </a>
    </div>
    <div class="mt-6">
        <button type="button" @click="openProfileModal = true" class="text-blue-600 hover:text-blue-800 font-semibold inline-flex items-center">
            <i class="fas fa-user-cog mr-2"></i> Gérer mon profil
        </button>
    </div>

    <!-- Modal Gestion Profil -->
    <div x-cloak x-show="openProfileModal" class="fixed inset-0 z-50 flex items-center justify-center" x-data="{ tab: 'infos' }">
        <div class="absolute inset-0 bg-black bg-opacity-50" @click="openProfileModal = false"></div>
        <div class="relative bg-white rounded-xl shadow-xl w-full max-w-xl mx-4">
            <div class="px-6 pt-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold">Mon profil</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-700" @click="openProfileModal = false"><i class="fas fa-times"></i></button>
                </div>
                <div class="mt-4 border-b">
                    <nav class="flex gap-4 text-sm">
                        <button type="button" class="py-2 border-b-2" :class="tab === 'infos' ? 'border-blue-600 text-blue-700 font-semibold' : 'border-transparent text-gray-600'" @click="tab = 'infos'">Informations</button>
                        <button type="button" class="py-2 border-b-2" :class="tab === 'password' ? 'border-blue-600 text-blue-700 font-semibold' : 'border-transparent text-gray-600'" @click="tab = 'password'">Mot de passe</button>
                    </nav>
                </div>
            </div>

            <div class="p-6">
                <!-- Onglet Informations -->
                <div x-show="tab === 'infos'">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nom</label>
                                <input type="text" name="name" value="{{ auth()->user()->name }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('name')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <p class="text-xs text-gray-500 mt-1">Si vous changez d'email, il devra être revalidé.</p>
                                @error('email')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-50" @click="openProfileModal = false">Annuler</button>
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Enregistrer</button>
                        </div>
                    </form>
                </div>

                <!-- Onglet Mot de passe -->
                <div x-show="tab === 'password'">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                @error('current_password')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Nouveau mot de passe</label>
                                <input type="password" name="password" minlength="8" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <p class="text-xs text-gray-500 mt-1">Au moins 8 caractères.</p>
                                @error('password')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="password_confirmation" minlength="8" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" class="px-4 py-2 rounded border text-gray-700 hover:bg-gray-50" @click="openProfileModal = false">Annuler</button>
                            <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
