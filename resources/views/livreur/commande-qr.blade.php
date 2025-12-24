@extends('livreur.layouts.livreur')

@section('title', 'QR de la commande ' . ($commande->numero_commande ?? $commande->id))

@section('livreur-content')
<div class="px-0">
    <h1 class="text-2xl font-bold mb-4">QR Code de la commande {{ $commande->numero_commande }}</h1>
    <div class="bg-white rounded-xl shadow p-6 text-center">
        <p class="text-gray-600 mb-4">Le client doit scanner ce code lors de la livraison pour confirmer la réception.</p>
        <img alt="QR code" class="mx-auto border rounded-lg shadow" src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data={{ urlencode($confirmationUrl) }}" />
        <div class="mt-4 text-xs text-gray-500 break-all">{{ $confirmationUrl }}</div>
    </div>

    <div class="mt-8 bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-2">Itinéraire et suivi en temps réel</h2>
        <p class="text-sm text-gray-600 mb-4">Votre position est partagée en temps réel pour cette commande. Assurez-vous d'accepter la géolocalisation dans votre navigateur. L'itinéraire est affiché sur la carte (OSRM + OpenStreetMap), sans Google Maps.</p>
        <div id="map-livreur" class="w-full h-80 rounded-lg border"></div>
        <div class="mt-3 text-xs text-gray-500" id="livreur-last-seen">Dernière mise à jour: —</div>
        <div class="mt-3 text-xs text-gray-500">Astuce: zoomez/dézoomez pour mieux voir le parcours.</div>
    </div>

    <div class="mt-6">
        <a href="{{ route('livreur.dashboard') }}" class="px-4 py-2 rounded bg-gray-100 hover:bg-gray-200">Retour</a>
    </div>
</div>

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+HM+YGyZf8Q0pY8H31sPpZBwS0C3lXQ=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function(){
    const mapEl = document.getElementById('map-livreur');
    if (!mapEl) return;

    const csrf = '{{ csrf_token() }}';
    const commandeId = {{ $commande->id }};
    const destinationAddress = @json($commande->pointRelais ? ($commande->pointRelais->nom . ', ' . $commande->pointRelais->adresse . ', ' . $commande->pointRelais->ville . ' ' . $commande->pointRelais->code_postal) : trim(($commande->adresse_livraison ?? '') + ', ' + ($commande->quartier_livraison ?? '') + ' - ' + ($commande->ville_livraison ?? '')));
    const destinationProvided = @json($commande->pointRelais && $commande->pointRelais->latitude && $commande->pointRelais->longitude ? [$commande->pointRelais->latitude, $commande->pointRelais->longitude] : null);

    const map = L.map('map-livreur').setView([6.1349, 1.2225], 13); // Default center (Lomé) à adapter si besoin
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    let livreurMarker = null;
    let destinationMarker = null;
    let routeLayer = null;

    function geocode(address) {
        return fetch('https://nominatim.openstreetmap.org/search?format=jsonv2&q=' + encodeURIComponent(address), {
            headers: { 'Accept': 'application/json' }
        }).then(r => r.json()).then(results => {
            if (results && results.length > 0) {
                const { lat, lon } = results[0];
                return [parseFloat(lat), parseFloat(lon)];
            }
            throw new Error('Destination introuvable');
        });
    }

    function setDestination(coords) {
        if (destinationMarker) { destinationMarker.setLatLng(coords); }
        else { destinationMarker = L.marker(coords, { title: 'Destination' }).addTo(map); }
    }

    function updateLivreurMarker(lat, lng) {
        const coords = [lat, lng];
        if (livreurMarker) { livreurMarker.setLatLng(coords); }
        else { livreurMarker = L.marker(coords, { title: 'Moi (Livreur)' }).addTo(map); }
    }

    function postPosition(lat, lng){
        fetch('/livreur/commande/' + commandeId + '/position', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ lat, lng })
        }).then(r => r.json()).then(data => {
            const el = document.getElementById('livreur-last-seen');
            if (data && data.updated_at && el) {
                el.textContent = 'Dernière mise à jour: ' + new Date(data.updated_at).toLocaleString();
            }
        }).catch(()=>{});
    }

    // Init destination
    let destCoords = destinationProvided;
    if (destCoords) {
        setDestination(destCoords);
        map.setView(destCoords, 14);
    } else {
        geocode(destinationAddress).then(coords => {
            destCoords = coords;
            setDestination(coords);
            map.setView(coords, 14);
        }).catch(()=>{});
    }

    function drawRoute(fromLat, fromLng, toCoords){
        if (!toCoords) return;
        const url = 'https://router.project-osrm.org/route/v1/driving/' + [fromLng, fromLat].join(',') + ';' + [toCoords[1], toCoords[0]].join(',') + '?overview=full&geometries=geojson';
        fetch(url).then(r => r.json()).then(json => {
            if (!json || !json.routes || !json.routes[0]) return;
            const geo = json.routes[0].geometry;
            if (routeLayer) { map.removeLayer(routeLayer); }
            routeLayer = L.geoJSON(geo, { style: { color: '#2563eb', weight: 4, opacity: 0.8 } }).addTo(map);
            try {
                const bounds = routeLayer.getBounds();
                if (bounds && bounds.isValid()) { map.fitBounds(bounds, { padding: [20,20] }); }
            } catch(e) {}
        }).catch(()=>{});
    }

    // Start geolocation tracking
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(pos){
            const { latitude, longitude } = pos.coords;
            updateLivreurMarker(latitude, longitude);
            postPosition(latitude, longitude);
            drawRoute(latitude, longitude, destCoords);
        }, function(err){ console.warn('Geolocation error', err); }, {
            enableHighAccuracy: true,
            maximumAge: 5000,
            timeout: 10000
        });
    }
})();
</script>
@endpush
@endsection
