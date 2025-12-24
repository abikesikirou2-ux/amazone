<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CommandeReceptionController extends Controller
{
    public function confirmer(string $token)
    {
        $commande = Commande::where('qr_token', $token)->first();

        if (! $commande) {
            return view('commandes.reception', [
                'ok' => false,
                'message' => "Lien invalide ou expiré.",
            ]);
        }

        if ($commande->recu_le) {
            return view('commandes.reception', [
                'ok' => true,
                'deja' => true,
                'commande' => $commande,
            ]);
        }

        $commande->recu_le = now();
        $commande->statut = $commande->statut ?: 'livree';
        $commande->save();

        return view('commandes.reception', [
            'ok' => true,
            'commande' => $commande,
        ]);
    }
}
