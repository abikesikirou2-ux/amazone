<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle commande lancée</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans'; color: #111827; }
        .muted { color: #6b7280; }
        .card { background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; padding:24px; }
    </style>
    </head>
<body style="background:#f3f4f6; padding:24px;">
    <div style="max-width:640px; margin:0 auto;">
        <h1 style="font-size:20px; margin-bottom:12px;">Mini Amazon — Nouvelle commande lancée</h1>
        <div class="card">
            <p><strong>Numéro:</strong> {{ $commande->numero_commande }}</p>
            <p><strong>Client:</strong> {{ $commande->user->name }} ({{ $commande->user->email }})</p>
            <p><strong>Total:</strong> @fcfa($commande->total)</p>
            <p><strong>Statut:</strong> {{ $commande->statut }}</p>
            <p class="muted">Une notification est visible dans l’espace admin (liste des commandes en attente).</p>
        </div>
    </div>
</body>
</html>
