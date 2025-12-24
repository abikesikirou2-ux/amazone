<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation compte livreur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <style>
        body { font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial; background:#f8fafc; color:#0f172a; }
        .container { max-width: 640px; margin: 40px auto; padding: 0 16px; }
        .card { background: #ffffff; border:1px solid #e2e8f0; border-radius: 14px; padding: 28px; }
        .title { margin: 0 0 8px; }
        .muted { color:#475569; }
        .ok { color:#065f46; background:#ecfdf5; border:1px solid #a7f3d0; }
        .ko { color:#991b1b; background:#fef2f2; border:1px solid #fecaca; }
        a.btn { display:inline-block; margin-top: 16px; background:#2563eb; color:#fff; padding:10px 16px; border-radius:10px; text-decoration:none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card {{ $success ? 'ok' : 'ko' }}">
            <h1 class="title">{{ $titre }}</h1>
            <p class="muted">{{ $message }}</p>
            <a class="btn" href="/livreur/login">Aller à la connexion livreur</a>
        </div>
    </div>
</body>
</html>
