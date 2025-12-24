@extends('layouts.store')

@section('title', 'Mes commandes - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Mes commandes</h1>

    <div class="grid lg:grid-cols-4 gap-6">
        <aside class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow p-4">
                <div class="font-bold mb-2">Espace client</div>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('compte') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-user mr-2"></i> Mon compte
                        </a>
                    </li>
                    <li>
                        <span class="inline-flex items-center text-gray-700">
                            <i class="fas fa-box mr-2"></i> Mes commandes
                        </span>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="lg:col-span-3">
    @if($commandes->isEmpty())
        <div class="bg-white rounded-xl shadow p-10 text-center">
            <i class="fas fa-box-open text-5xl text-gray-300 mb-3"></i>
            <p class="text-gray-600">Vous n'avez pas encore de commandes.</p>
            <a href="{{ route('produits') }}" class="inline-block mt-4 bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700">Découvrir des produits</a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 text-left text-sm text-gray-600">
                    <tr>
                        <th class="p-4">Commande</th>
                        <th class="p-4">Date</th>
                        <th class="p-4">Statut</th>
                        <th class="p-4">Total</th>
                        <th class="p-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($commandes as $cmd)
                        <tr>
                            <td class="p-4 font-semibold">{{ $cmd->numero_commande }}</td>
                            <td class="p-4">{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                            <td class="p-4"><span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs">{{ ucfirst(str_replace('_',' ', $cmd->statut)) }}</span></td>
                            <td class="p-4 font-bold">@fcfa($cmd->total)</td>
                            <td class="p-4 text-right">
                                <a href="{{ route('commande.show', $cmd->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold">Détails</a>
                                @if(!$cmd->recu_le)
                                    @php($qrUrl = $cmd->lienConfirmationReception())
                                    <a href="{{ $qrUrl }}" target="_blank" class="ml-3 text-purple-600 hover:text-purple-800 font-semibold"><i class="fas fa-qrcode mr-1"></i> Réception</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $commandes->links() }}</div>
    @endif
        </div>
    </div>
</div>
@endsection
