# Configuration SMTP avec Gmail (Option B)

## Étapes

1. Activer la validation en deux étapes (2FA) sur votre compte Gmail
   - Compte Google > Sécurité > Validation en deux étapes.

2. Créer un "Mot de passe d'application"
   - Compte Google > Sécurité > Mots de passe d'application.
   - Choisir Application: "Autre (Nom personnalisé)" (ex: Mini Amazon) et générer.
   - Copiez le mot de passe généré.

3. Remplir les variables dans `.env`
   - Exemple prêt à l'emploi: voir `.env.gmail.example`.
   - Champs obligatoires:
     - `MAIL_MAILER=smtp`
     - `MAIL_HOST=smtp.gmail.com`
     - `MAIL_PORT=587`
     - `MAIL_USERNAME=ton.email@gmail.com`
     - `MAIL_PASSWORD=<mot de passe d'application>`
     - `MAIL_ENCRYPTION=tls`
     - `MAIL_FROM_ADDRESS=ton.email@gmail.com`
     - `MAIL_FROM_NAME="Mini Amazon"`
     - `APP_URL=http://127.0.0.1:8000`
     - `QUEUE_CONNECTION=sync`

4. Recharger la configuration et les vues

```powershell
php artisan config:clear
php artisan config:cache
php artisan view:clear
php artisan view:cache
```

5. Tester l'envoi

- Méthode A: Bouton "Renvoyer validation" / "Forcer envoi" dans l'admin Livreurs.
- Méthode B (rapide): route d'auto‑test `/admin/email-test` (voir ci‑dessous).

## Route de test (admin)

- Accessible uniquement aux administrateurs connectés.
- Envoie un e‑mail de test sur votre adresse utilisateur.
- Retourne un message de succès ou d'erreur.

Si l'email n'arrive pas:
- Vérifiez `storage/logs/laravel.log`.
- Consultez le dossier spam.
- Confirmez le port (587) et l'encryption (tls) ne sont pas bloqués.
- Assurez `QUEUE_CONNECTION=sync` (ou lancez `php artisan queue:work`).

## Bonnes pratiques (prod)
- Utiliser un expéditeur du domaine (ex: `no-reply@votre-domaine.com`).
- Configurer SPF, DKIM, DMARC pour améliorer la délivrabilité.
- Éviter d'utiliser Gmail en prod; privilégier un service SMTP dédié (SendGrid, Mailgun, Postmark) ou le SMTP de votre hébergeur.
