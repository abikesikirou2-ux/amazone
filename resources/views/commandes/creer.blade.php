@extends('layouts.store')

@section('title', 'Passer la commande - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold mb-6">Finaliser ma commande</h1>

    <form action="{{ route('commande.enregistrer') }}" method="POST" x-data="commandeForm()">
        @csrf
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Adresse et livraison -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Adresse de livraison</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1">Adresse</label>
                            <input name="adresse_livraison" type="text" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Ville</label>
                            <input name="ville_livraison" type="text" class="w-full border rounded px-3 py-2" required x-model="ville">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1">Quartier (optionnel)</label>
                            <input name="quartier_livraison" type="text" class="w-full border rounded px-3 py-2" x-model="quartier">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Mode de livraison</h2>
                    <div class="space-y-3">
                        @foreach($modesLivraison as $mode)
                        <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="mode_livraison_id" value="{{ $mode->id }}" class="mt-1" required x-model="modeId">
                            <div class="flex-1">
                                <div class="font-semibold">{{ $mode->nom }}</div>
                                <div class="text-sm text-gray-600">{{ $mode->description }}</div>
                            </div>
                                    <div class="font-bold">@fcfa($mode->prix)</div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow p-6" x-show="typeLivraison === 'relais'">
                    <h2 class="text-xl font-bold mb-4">Point relais</h2>
                    <div class="flex gap-3 mb-3">
                        <input type="text" placeholder="Code postal" class="border rounded px-3 py-2" x-model="codePostal">
                        <button type="button" class="bg-gray-800 text-white px-4 py-2 rounded" @click="chercherPointsRelais">Rechercher</button>
                    </div>
                    <input type="hidden" name="point_relais_id" :value="pointRelaisId">
                    <template x-if="points.length">
                        <div class="space-y-2">
                            <template x-for="p in points" :key="p.id">
                                <label class="flex items-start gap-3 p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                    <input type="radio" name="point_relais" :value="p.id" class="mt-1" @change="pointRelaisId = p.id">
                                    <div>
                                        <div class="font-semibold" x-text="p.nom"></div>
                                        <div class="text-sm text-gray-600" x-text="p.adresse + ', ' + p.ville + ' ' + p.code_postal"></div>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </template>
                    <p class="text-sm text-gray-500" x-show="!points.length">Aucun point relais chargé.</p>
                </div>

                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Code promo</h2>
                    <input type="text" name="coupon_code" placeholder="Entrez votre code" class="border rounded px-3 py-2">
                    <p class="text-sm text-gray-500 mt-2">Si vous avez reçu un code par e‑mail, saisissez‑le ici.</p>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div>
                <div class="bg-white rounded-xl shadow p-6">
                    <h2 class="text-xl font-bold mb-4">Récapitulatif</h2>
                    <div class="space-y-2 text-sm">
                        @foreach($panier->articles as $a)
                            <div class="flex justify-between"><span>{{ $a->produit->nom }} × {{ $a->quantite }}</span><span>@fcfa($a->getSousTotal())</span></div>
                        @endforeach
                            <div class="flex justify-between border-t pt-2 font-semibold"><span>Sous-total</span><span>@fcfa($panier->obtenirTotal())</span></div>
                        <div class="text-xs text-gray-500">Frais de livraison et réductions appliqués après validation</div>
                    </div>
                    <input type="hidden" name="type_livraison" :value="typeLivraison">
                    <button type="submit" class="mt-6 w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">Confirmer et payer plus tard</button>
                </div>
            </div>
        </div>
    </form>
</div>

@section('scripts')
<script>
function commandeForm(){
    return {
        ville: '', quartier: '', modeId: null, codePostal: '', points: [], pointRelaisId: null,
        get typeLivraison(){
            // Simple heuristic: if mode name contains 'relais' on server side it's set; here we leave manual toggle by points search
            return this.points.length ? 'relais' : 'domicile';
        },
        async chercherPointsRelais(){
            if(!this.codePostal) return;
            const res = await fetch('{{ route('api.points-relais') }}', {method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}, body: JSON.stringify({code_postal: this.codePostal})});
            if(res.ok){ this.points = await res.json(); }
        }
    }
}
</script>
@endsection
@endsection
