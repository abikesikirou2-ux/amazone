@extends('layouts.store')

@section('title', 'Catégories - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Catégories</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
        @php($categories = \App\Models\Categorie::withCount('produits')->get())
        @foreach($categories as $categorie)
            <a href="{{ route('categorie.produits', $categorie->id) }}" class="group">
                <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-xl transition transform hover:-translate-y-2">
                    <div class="bg-white ring-1 ring-gray-200 w-20 h-20 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition">
                        <i class="{{ $categorie->icone }} {{ $categorie->couleur }} text-3xl"></i>
                    </div>
                    <h3 class="text-center font-semibold text-base group-hover:text-blue-600 transition">{{ $categorie->nom }}</h3>
                    <p class="text-center text-sm text-gray-500 mt-1">{{ $categorie->produits_count }} produits</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
