@extends('layouts.admin')

@section('title', 'Admin - Catégories')

@section('admin-content')
<div class="px-0">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Catégories</h1>
        <a href="{{ route('admin.categories.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Nouvelle catégorie
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
                    <th class="px-4 py-2 text-left">Description</th>
                    <th class="px-4 py-2 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($categories as $c)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $c->nom }}</td>
                    <td class="px-4 py-2">{{ Str::limit($c->description, 80) }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.categories.edit', $c->id) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-edit"></i></a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $c->id) }}" class="inline">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:text-red-800" onclick="return confirm('Supprimer cette catégorie ?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection
