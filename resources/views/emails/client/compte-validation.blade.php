<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de compte client</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji'; color: #111827; }
        .btn { display: inline-block; background: #2563eb; color: #fff !important; padding: 10px 16px; border-radius: 8px; text-decoration: none; }
        .muted { color: #6b7280; }
        .card { background:#ffffff; border:1px solid #e5e7eb; border-radius:12px; padding:24px; }
    </style>
    </head>
<body style="background:#f3f4f6; padding:24px;">
    <div style="max-width:640px; margin:0 auto;">
        <div style="text-align:center; margin-bottom:16px;">
            <div style="display:inline-flex; align-items:center; justify-content:center; width:48px; height:48px; background:#1d4ed8; color:#fff; border-radius:12px;">
                <span style="font-size:22px;">👤</span>
            </div>
            <h1 style="margin:12px 0 0; font-size:22px;">Bienvenue, {{ $user->name }} !</h1>
            <div class="muted" style="font-size:14px;">Validation de votre compte client</div>
        </div>

        <div class="card">
            <p>Bonjour {{ $user->name }},</p>
            <p>Votre compte client a été créé avec succès sur Mini Amazon. Pour activer votre compte, merci de <strong>valider votre adresse e‑mail</strong> en cliquant sur le bouton ci‑dessous :</p>

            <div style="margin:16px 0;">
                <a class="btn" href="{{ $verificationUrl }}" target="_blank" rel="noopener">Valider mon compte client</a>
            </div>

            <p style="margin-top:8px;" class="muted">Ce lien est à usage unique et expirera après un délai limité.</p>

            <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;" />

            <p>Vos identifiants :</p>
            <ul style="list-style:none; padding:0; margin:8px 0;">
                <li><strong>Email:</strong> {{ $user->email }}</li>
            </ul>

            <p style="margin-top:20px;">
                <a class="btn" href="{{ url('/login') }}" target="_blank" rel="noopener">Se connecter comme client</a>
            </p>

            <p style="margin-top:24px;" class="muted">Si vous n’êtes pas à l’origine de cette inscription, ignorez cet e‑mail.</p>
        </div>

        <p class="muted" style="text-align:center; font-size:12px; margin-top:16px;">&copy; {{ date('Y') }} Mini Amazon</p>
    </div>
</body>
</html>
