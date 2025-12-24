<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de disponibilité — Commande</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans'; color: #111827; }
        .btn { display: inline-block; background: #2563eb; color: #fff !important; padding: 10px 16px; border-radius: 8px; text-decoration: none; }
        .muted { color: #6b7280; }
        .card { background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; padding:24px; }
    </style>
    </head>
<body style="background:#f3f4f6; padding:24px;">
    <div style="max-width:640px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:16px;">
            <div style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; background:#1d4ed8; color:#fff; border-radius:12px;">
                <span style="font-size:22px;">🚚</span>
            </div>
            <h1 style="margin:12px 0 0; font-size:22px;">Nouvelle commande assignée</h1>
            <div class="muted" style="font-size:14px;">Merci de confirmer votre disponibilité</div>
        </div>

        <div class="card">
            <p>Bonjour {{ $livreur->prenom }},</p>
            <p>Une nouvelle commande <strong>{{ $commande->numero_commande }}</strong> à livrer à {{ $commande->ville_livraison }} {{ $commande->quartier_livraison }} vient de vous être assignée.</p>

            <ul style="list-style:none; padding:0; margin:8px 0;">
                <li><strong>Client:</strong> {{ $commande->user->name }}</li>
                <li><strong>Total:</strong> @fcfa($commande->total)</li>
                <li><strong>Adresse:</strong> {{ $commande->adresse_livraison }}</li>
            </ul>

            <div style="margin:16px 0;">
                <a class="btn" href="{{ $acceptUrl }}" target="_blank" rel="noopener" style="background:#059669;">Oui, disponible</a>
                <span style="display:inline-block; width:8px;"></span>
                <a class="btn" href="{{ $refuseUrl }}" target="_blank" rel="noopener" style="background:#dc2626;">Non</a>
            </div>

            <p class="muted" style="font-size:14px;">Ces liens sont sécurisés et expirent après un délai limité.</p>
        </div>

        <p class="muted" style="text-align:center; font-size:12px; margin-top:16px;">&copy; {{ date('Y') }} Mini Amazon</p>
    </div>
</body>
</html>
