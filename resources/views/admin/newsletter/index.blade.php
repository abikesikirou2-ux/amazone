@extends('layouts.admin')

@section('title', 'Inscriptions Newsletter - Mini Amazon')

@section('admin-content')
<div class="px-0">
    <h1 class="text-3xl font-bold mb-6">Inscriptions Newsletter</h1>
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 text-left text-sm text-gray-600">
                <tr>
                    <th class="p-4">Email</th>
                    <th class="p-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($inscriptions as $i)
                    <tr>
                        <td class="p-4">{{ $i->email }}</td>
                        <td class="p-4">{{ $i->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-4" colspan="2">Aucune inscription pour le moment.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">{{ $inscriptions->links() }}</div>
</div>
@endsection
