<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Coupon fidélité attribué</title>
    <style>
        body { font-family: Arial, sans-serif; color:#111; }
        .card { max-width:600px; margin:0 auto; border:1px solid #e5e7eb; border-radius:12px; padding:24px; }
        .muted { color:#6b7280; font-size:12px; }
    </style>
    </head>
<body>
    <div class="card">
        <h1 style="margin-top:0;">Coupon fidélité attribué</h1>
        <p>Un nouveau coupon fidélité a été attribué au client :</p>
        <ul>
            <li><strong>Client:</strong> {{ $client->name }} &lt;{{ $client->email }}&gt;</li>
            <li><strong>Code:</strong> {{ $coupon->code }}</li>
            <li><strong>Type:</strong> Pourcentage 10%</li>
            <li><strong>Validité:</strong> du {{ optional($coupon->date_debut)->format('d/m/Y') }} au {{ optional($coupon->date_fin)->format('d/m/Y') }}</li>
            <li><strong>Utilisations:</strong> {{ $coupon->compteur_utilisation }} / {{ $coupon->utilisations_max }}</li>
        </ul>
        <p class="muted">Vous pouvez gérer les coupons via la page Administration &gt; Codes promos clients.</p>
    </div>
</body>
</html>
