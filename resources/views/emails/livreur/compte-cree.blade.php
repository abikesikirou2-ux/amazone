<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validation de compte livreur</title>
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
                <span style="font-size:22px;">🚚</span>
            </div>
            <h1 style="margin:12px 0 0; font-size:22px;">Bienvenue, {{ $livreur->prenom }} {{ $livreur->nom }} !</h1>
            <div class="muted" style="font-size:14px;">Validation de votre compte livreur</div>
        </div>

        <div class="card">
            <p>Bonjour {{ $livreur->prenom }},</p>
            <p>Votre compte livreur a été créé avec succès par l’équipe Mini Amazon. Merci de confirmer ci-dessous si vous <strong>acceptez</strong> d’activer ce compte :</p>

            <div style="margin:16px 0;">
                <a class="btn" href="{{ route('livreur.validation.accept', $livreur->validation_token) }}" target="_blank" rel="noopener" style="background:#059669;">Oui, activer mon compte</a>
                <span style="display:inline-block; width:8px;"></span>
                <a class="btn" href="{{ route('livreur.validation.refuse', $livreur->validation_token) }}" target="_blank" rel="noopener" style="background:#dc2626;">Non</a>
            </div>

            <p style="margin-top:8px;" class="muted">Ces liens sont à usage unique.</p>

            <hr style="border:none; border-top:1px solid #e5e7eb; margin:20px 0;" />

            <p>Vos identifiants (après validation) :</p>
            <ul style="list-style:none; padding:0; margin:8px 0;">
                <li><strong>Email:</strong> {{ $livreur->email }}</li>
                @if(!empty($plainPassword))
                    <li><strong>Mot de passe temporaire:</strong> {{ $plainPassword }}</li>
                @endif
            </ul>

            <p class="muted" style="font-size:14px;">Pour des raisons de sécurité, merci de changer votre mot de passe après la première connexion.</p>

            <ul style="list-style:none; padding:0; margin:16px 0;">
                <li><strong>Ville:</strong> {{ $livreur->ville }}</li>
                @if($livreur->quartier)
                    <li><strong>Quartier:</strong> {{ $livreur->quartier }}</li>
                @endif
                <li><strong>Téléphone:</strong> {{ $livreur->telephone }}</li>
            </ul>

            <p style="margin-top:20px;">
                <a class="btn" href="{{ url('/livreur/login') }}" target="_blank" rel="noopener">Se connecter comme livreur</a>
            </p>

            <p style="margin-top:24px;" class="muted">Si vous n’êtes pas à l’origine de cette inscription, cliquez sur « Non » ou ignorez cet email.</p>
        </div>

        <p class="muted" style="text-align:center; font-size:12px; margin-top:16px;">&copy; {{ date('Y') }} Mini Amazon</p>
    </div>
</body>
</html>
