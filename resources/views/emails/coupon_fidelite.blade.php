<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Code promo fidélité</title>
    <style>
        body { font-family: Arial, sans-serif; color:#111; }
        .card { max-width:600px; margin:0 auto; border:1px solid #e5e7eb; border-radius:12px; padding:24px; }
        .code { font-size:24px; font-weight:bold; letter-spacing:2px; }
        .muted { color:#6b7280; font-size:12px; }
        .btn { display:inline-block; background:#1d4ed8; color:#fff; padding:10px 16px; border-radius:8px; text-decoration:none; }
    </style>
    </head>
<body>
    <div class="card">
        <h1 style="margin-top:0;">Merci pour votre fidélité, {{ $user->name }} !</h1>
        <p>Vous avez atteint 10 achats cette année sur Mini Amazon. Voici votre code promo personnel :</p>
        <p class="code">{{ $coupon->code }}</p>
        <p>Réduction: <strong>10%</strong> sur votre commande. Ce code est <strong>réservé à votre compte</strong> et valable une seule fois.</p>
        <p>Validité: du {{ optional($coupon->date_debut)->format('d/m/Y') }} au {{ optional($coupon->date_fin)->format('d/m/Y') }}.</p>
        <p class="muted">Un seul avantage par commande: code promo OU réduction de période.</p>
        <p>
            <a class="btn" href="{{ url('/') }}">Visiter la boutique</a>
        </p>
        <p class="muted">Si vous n’êtes pas à l’origine de cet e-mail, vous pouvez l’ignorer.</p>
    </div>
</body>
</html>
