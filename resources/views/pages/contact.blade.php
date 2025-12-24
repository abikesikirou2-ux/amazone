@extends('layouts.store')
@section('title', 'Contact - Mini Amazon')
@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Contact</h1>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Coordonnées -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4">Nos coordonnées</h2>
            <ul class="space-y-3 text-sm">
                <li class="flex items-center">
                    <i class="fas fa-envelope mr-2"></i>
                    <a href="https://mail.google.com/mail/?view=cm&fs=1&to=mimiamazone555@gmail.com" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800">mimiamazone555@gmail.com</a>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-phone mr-2"></i>
                    <a href="tel:+2290154253797" class="text-blue-600 hover:text-blue-800">+229 01 54 25 37 97</a>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-map-marker-alt mt-1 mr-2"></i>
                    <span>BENIN / PORTO-NOVO / DJEGAN-KPEVI</span>
                </li>
            </ul>
            <p class="text-sm text-gray-600 mt-4">Nous répondons généralement sous 24h.</p>
        </div>

        <!-- Formulaire de contact -->
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-xl font-bold mb-4">Envoyer un message</h2>
            <form method="POST" action="{{ route('contact.envoyer') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1">Nom</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('nom')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Sujet</label>
                    <input type="text" name="sujet" value="{{ old('sujet') }}" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('sujet')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Message</label>
                    <textarea name="message" rows="5" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ old('message') }}</textarea>
                    @error('message')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
