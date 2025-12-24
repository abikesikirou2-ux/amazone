@extends('layouts.app')

@section('title', 'Authentification requise - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <div class="bg-white rounded-xl shadow-lg p-10 max-w-2xl mx-auto text-center">
        <i class="fas fa-lock text-5xl text-gray-300 mb-4"></i>
        <h1 class="text-2xl font-bold mb-3">Authentification non configurée</h1>
        <p class="text-gray-600 mb-6">Les pages de connexion / inscription ne sont pas encore installées.</p>
        <div class="text-left bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-900">
            <p class="font-semibold mb-2">Pour activer l'authentification (recommandé):</p>
            <pre class="whitespace-pre-wrap">composer require laravel/breeze --dev
php artisan breeze:install blade
npm install
npm run build</pre>
        </div>
        <a href="{{ route('accueil') }}" class="inline-block mt-6 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">Retour à l'accueil</a>
    </div>
    
</div>
@endsection
