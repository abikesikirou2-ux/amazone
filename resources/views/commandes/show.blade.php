@extends('layouts.store')

@section('title', 'Détails commande - Mini Amazon')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold">Commande {{ $commande->numero_commande }}</h1>
        <a href="{{ route('commandes') }}" class="text-blue-600 hover:text-blue-800 font-semibold"><i class="fas fa-angle-left mr-1"></i> Mes commandes</a>
    </div>

    <div class="grid lg:grid-cols-4 gap-8">
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
                        <a href="{{ route('commandes') }}" class="inline-flex items-center text-gray-700 hover:text-gray-900">
                            <i class="fas fa-box mr-2"></i> Mes commandes
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm text-gray-600">Statut</div>
                        <div class="font-bold">{{ ucfirst(str_replace('_',' ', $commande->statut)) }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Passée le</div>
                        <div class="font-semibold">{{ $commande->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Mode de livraison</div>
                        <div class="font-semibold">{{ $commande->modeLivraison->nom }}</div>
                    </div>
                </div>
                @if($commande->coupon_id)
                    <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-sm font-medium text-emerald-700">
                        <i class="fas fa-ticket-alt"></i>
                        Code promo appliqué: <span class="ml-1">{{ $commande->coupon->code }}</span>
                        @if($commande->coupon->type === 'pourcentage')
                            <span class="ml-2">(-{{ $commande->coupon->valeur }}%)</span>
                        @else
                            <span class="ml-2">(- @fcfa($commande->coupon->valeur))</span>
                        @endif
                        @if($commande->coupon->livraison_gratuite)
                            <span class="ml-2 inline-flex items-center gap-1 text-emerald-800"><i class="fas fa-truck"></i> Livraison gratuite</span>
                        @endif
                    </div>
                @elseif($commande->reduction > 0)
                    <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-sm font-medium text-blue-700">
                        <i class="fas fa-badge-percent"></i>
                        Réduction de période appliquée
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Articles</h2>
                <div class="divide-y">
                    @foreach($commande->articles as $a)
                        <div class="py-4 flex items-center justify-between">
                            <div>
                                <div class="font-semibold">{{ $a->produit->nom }}</div>
                                <div class="text-sm text-gray-600">{{ $a->quantite }} × @fcfa($a->prix)</div>
                            </div>
                            <div class="font-bold">@fcfa($a->getSousTotal())</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="text-xl font-bold mb-4">Livraison</h2>
                @if($commande->pointRelais)
                    <div class="text-sm">
                        <div class="font-semibold">Point relais</div>
                        <div>{{ $commande->pointRelais->nom }}</div>
                        <div class="text-gray-600">{{ $commande->pointRelais->adresse }}, {{ $commande->pointRelais->ville }} {{ $commande->pointRelais->code_postal }}</div>
                    </div>
                @else
                    <div class="text-sm">
                        <div class="font-semibold">Adresse</div>
                        <div>{{ $commande->adresse_livraison }}</div>
                        <div class="text-gray-600">{{ $commande->quartier_livraison ? $commande->quartier_livraison.', ' : '' }}{{ $commande->ville_livraison }}</div>
                    </div>
                @endif

                @if($commande->livreur)
                    <div class="mt-4 text-sm">
                        <div class="font-semibold">Livreur</div>
                        <div>{{ $commande->livreur->prenom }} {{ $commande->livreur->nom }} — {{ $commande->livreur->telephone }}</div>
                    </div>
                @endif
            </div>

            <div class="bg-white rounded-xl shadow p-6 mt-6">
                <h2 class="text-xl font-bold mb-4">Récapitulatif</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span>Sous-total</span><span>@fcfa($commande->sous_total)</span></div>
                    <div class="flex justify-between"><span>Livraison</span><span>@fcfa($commande->prix_livraison)</span></div>
                    <div class="flex justify-between"><span>Réduction</span><span>- @fcfa($commande->reduction)</span></div>
                    <div class="flex justify-between border-t pt-2 font-bold text-lg"><span>Total</span><span class="text-blue-600">@fcfa($commande->total)</span></div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 mt-6">
                <h2 class="text-xl font-bold mb-3">Actions</h2>
                <div class="flex flex-col gap-3">
                    @php
                        $destination = $commande->pointRelais
                            ? ($commande->pointRelais->nom . ', ' . $commande->pointRelais->adresse . ', ' . $commande->pointRelais->ville . ' ' . $commande->pointRelais->code_postal)
                            : trim(($commande->adresse_livraison ?? '') . ', ' . ($commande->quartier_livraison ?? '') . ' - ' . ($commande->ville_livraison ?? ''));
                    @endphp
                    <div class="w-full inline-flex items-center justify-center px-4 py-2 rounded bg-blue-600 text-white font-semibold">
                        <i class="fas fa-route mr-2"></i>
                        Itinéraire affiché sur la carte (OpenStreetMap)
                    </div>
                    @if($commande->statut !== 'payee')
                    <form action="{{ route('commande.payer', $commande->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 rounded bg-green-600 text-white font-semibold hover:bg-green-700">
                            <i class="fas fa-credit-card mr-2"></i>
                            Simuler paiement et recevoir le reçu PDF
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('commande.scanner') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded bg-blue-600 text-white font-semibold hover:bg-blue-700">
                        <i class="fas fa-qrcode mr-2"></i>
                        Ouvrir le scanneur de QR
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow p-6 mt-6">
                <h2 class="text-xl font-bold mb-3">Suivi en temps réel du livreur</h2>
                <div id="map-client" class="w-full h-80 rounded-lg border"></div>
                <div class="mt-2 text-sm text-red-600" id="map-status" style="display:none">Service itinéraire hors ligne</div>
                <div class="mt-3 text-xs text-gray-500" id="client-last-seen">Dernière activité livreur: —</div>
            </div>

            @if(!$commande->recu_le)
            <div class="bg-white rounded-xl shadow p-6 mt-6">
                <h2 class="text-xl font-bold mb-2">Confirmation de réception</h2>
                <p class="text-sm text-gray-600 mb-4">Scannez ce QR code lors de la réception de votre commande pour confirmer la livraison.</p>
                @php($qrUrl = $commande->lienConfirmationReception())
                <div class="flex flex-col items-center">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($qrUrl) }}" alt="QR confirmation réception" class="rounded-lg border" />
                    <div class="mt-3 text-xs text-gray-500 break-all">{{ $qrUrl }}</div>
                    <a href="{{ $qrUrl }}" target="_blank" class="mt-3 inline-flex items-center px-3 py-2 rounded bg-blue-600 text-white text-sm hover:bg-blue-700">
                        Ouvrir le lien de confirmation
                        <i class="fas fa-external-link-alt ml-2"></i>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+HM+YGyZf8Q0pY8H31sPpZBwS0C3lXQ=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function(){
    const mapEl = document.getElementById('map-client');
    if (!mapEl) return;

    const commandeId = {{ $commande->id }};
    const destinationAddress = @json($destination);
    const destinationProvided = @json($commande->pointRelais && $commande->pointRelais->latitude && $commande->pointRelais->longitude ? [$commande->pointRelais->latitude, $commande->pointRelais->longitude] : null);

    const map = L.map('map-client').setView([6.1349, 1.2225], 13); // Default center
    const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    tileLayer.on('tileerror', function(){
        showMapStatus('Cartes indisponibles (tuiles hors ligne)');
    });

    let livreurMarker = null;
    let destinationMarker = null;
    let routeLayer = null;

    function geocode(address) {
        return fetch('https://nominatim.openstreetmap.org/search?format=jsonv2&q=' + encodeURIComponent(address), {
            headers: { 'Accept': 'application/json' }
        }).then(r => r.json()).then(results => {
            if (results && results.length > 0) {
                const { lat, lon } = results[0];
                hideMapStatus();
                return [parseFloat(lat), parseFloat(lon)];
            }
            showMapStatus('Adresse introuvable');
            throw new Error('Destination introuvable');
        }).catch(()=>{ showMapStatus('Service de géocodage hors ligne'); throw new Error('Géocoding failed'); });
    }

    function setDestination(coords) {
        if (destinationMarker) { destinationMarker.setLatLng(coords); }
        else { destinationMarker = L.marker(coords, { title: 'Destination' }).addTo(map); }
    }

    function updateLivreurMarker(lat, lng) {
        const coords = [lat, lng];
        if (livreurMarker) { livreurMarker.setLatLng(coords); }
        else { livreurMarker = L.marker(coords, { title: 'Livreur' }).addTo(map); }
    }

    function showMapStatus(msg){
        const el = document.getElementById('map-status');
        if (!el) return;
        el.textContent = msg;
        el.style.display = 'block';
    }
    function hideMapStatus(){
        const el = document.getElementById('map-status');
        if (!el) return;
        el.style.display = 'none';
    }

    function pollPosition(){
        fetch('/commande/' + commandeId + '/position')
            .then(r => r.json())
            .then(data => {
                if (data && data.lat && data.lng) {
                    updateLivreurMarker(data.lat, data.lng);
                    if (window.__destCoords) {
                        drawRoute(data.lat, data.lng, window.__destCoords);
                    }
                }
                const el = document.getElementById('client-last-seen');
                if (el && data && data.last_seen_at) {
                    el.textContent = 'Dernière activité livreur: ' + new Date(data.last_seen_at).toLocaleString();
                }
            })
            .catch(()=>{});
    }

    function drawRoute(fromLat, fromLng, toCoords){
        if (!toCoords) return;
        const url = 'https://router.project-osrm.org/route/v1/driving/' + [fromLng, fromLat].join(',') + ';' + [toCoords[1], toCoords[0]].join(',') + '?overview=full&geometries=geojson';
        fetch(url).then(r => r.json()).then(json => {
            if (!json || !json.routes || !json.routes[0]) {
                showMapStatus('Service itinéraire hors ligne');
                return;
            }
            const geo = json.routes[0].geometry;
            if (routeLayer) { map.removeLayer(routeLayer); }
            routeLayer = L.geoJSON(geo, { style: { color: '#2563eb', weight: 4, opacity: 0.8 } }).addTo(map);
            hideMapStatus();
        }).catch(()=>{ showMapStatus('Service itinéraire hors ligne'); });
    }

    if (destinationProvided) {
        window.__destCoords = destinationProvided;
        setDestination(destinationProvided);
        map.setView(destinationProvided, 14);
    } else {
        geocode(destinationAddress).then(coords => {
            window.__destCoords = coords;
            setDestination(coords);
            map.setView(coords, 14);
        }).catch(()=>{});
    }

    pollPosition();
    setInterval(pollPosition, 5000);
})();
</script>
@endpush
@endsection
