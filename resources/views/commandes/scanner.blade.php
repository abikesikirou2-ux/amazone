@extends('layouts.store')
@section('title', 'Scanner QR - Mini Amazon')
@section('content')
<div class="container mx-auto px-4">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-2xl shadow mb-6 p-6">
        <h1 class="text-2xl font-bold">Scanner le code QR</h1>
        <p class="text-blue-100">Scannez le QR de confirmation pour valider la réception</p>
    </div>

    <div class="bg-white rounded-xl shadow p-6">
        <div id="qr-reader" style="width:100%"></div>
        <div id="qr-result" class="mt-4 text-sm text-gray-700"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.9/html5-qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        const qrRegionId = 'qr-reader';
        const html5QrCode = new Html5Qrcode(qrRegionId);
        const qrResult = document.getElementById('qr-result');

        function onScanSuccess(decodedText, decodedResult) {
            qrResult.textContent = 'QR détecté: ' + decodedText;
            // Si le QR est un lien de confirmation de réception, rediriger
            if (decodedText.includes('/commande/reception/')) {
                window.location.href = decodedText;
            }
            html5QrCode.stop();
        }

        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                const cameraId = devices[0].id;
                html5QrCode.start(cameraId, { fps: 10, qrbox: { width: 250, height: 250 } }, onScanSuccess);
            } else {
                qrResult.textContent = 'Aucune caméra détectée.';
            }
        }).catch(err => {
            qrResult.textContent = 'Erreur caméra: ' + err;
        });
    });
</script>
@endsection
