<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { text-align:center; margin-bottom: 12px; }
        .card { border:1px solid #e5e7eb; border-radius:8px; padding:16px; }
        .muted { color:#6b7280; }
        table { width:100%; border-collapse: collapse; }
        th, td { padding:8px; border-bottom:1px solid #e5e7eb; text-align:left; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Reçu de paiement — Mini Amazon</h2>
        <div class="muted">Commande {{ $commande->numero_commande }}</div>
    </div>

    <div class="card">
        <p><strong>Client:</strong> {{ $commande->user->name }} — {{ $commande->user->email }}</p>
        <p><strong>Date:</strong> {{ now()->format('d/m/Y H:i') }}</p>
        <p><strong>Adresse de livraison:</strong> {{ $commande->adresse_livraison }} {{ $commande->ville_livraison }} {{ $commande->quartier_livraison }}</p>

        <h3>Articles</h3>
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Quantité</th>
                    <th>Prix</th>
                    <th>Sous-total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->articles as $a)
                    <tr>
                        <td>{{ $a->produit->nom }}</td>
                        <td>{{ $a->quantite }}</td>
                        <td>@fcfa($a->prix)</td>
                        <td>@fcfa($a->getSousTotal())</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Récapitulatif</h3>
        <table>
            <tr><td>Sous-total</td><td>@fcfa($commande->sous_total)</td></tr>
            <tr><td>Livraison</td><td>@fcfa($commande->prix_livraison)</td></tr>
            <tr><td>Réduction</td><td>- @fcfa($commande->reduction)</td></tr>
            <tr><th>Total payé</th><th>@fcfa($commande->total)</th></tr>
        </table>
    </div>

    <p class="muted" style="margin-top:12px;">Merci pour votre achat. Conservez ce reçu.</p>
</body>
</html>
