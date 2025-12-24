<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CommandeController extends Controller
{
    public function qr(int $id)
    {
        $livreur = Auth::guard('livreur')->user();
        $commande = Commande::where('id', $id)->where('livreur_id', $livreur->id)->firstOrFail();
        $confirmationUrl = $commande->lienConfirmationReception();
        return view('livreur.commande-qr', compact('commande', 'confirmationUrl'));
    }

    public function livrer(Request $request, int $id)
    {
        $livreur = Auth::guard('livreur')->user();
        $commande = Commande::where('id', $id)->where('livreur_id', $livreur->id)->firstOrFail();

        if ($commande->statut === 'livree') {
            return back()->with('success', 'Commande déjà marquée comme livrée.');
        }

        // Verrouillage: marquage "livrée" seulement si QR scanné (recu_le renseigné)
        if (! $commande->recu_le) {
            return back()->with('error', 'Veuillez d\'abord faire scanner le QR par le client pour confirmer la réception.');
        }

        $commande->statut = 'livree';
        $commande->save();

        return back()->with('success', 'Commande marquée comme livrée.');
    }

    // Liens signés envoyés par e-mail au livreur pour confirmer/infirmer sa dispo
    public function accepterDisponibilite(Request $request, int $id)
    {
        $request->validate([]);
        // Route protégée par signature, le livreur n'a pas besoin d'être authentifié ici
        $commande = Commande::findOrFail($id);
        if ($commande->livreur_id) {
            $commande->statut = 'assignée';
            $commande->save();
        }
        return view('livreur.validation-result', [
            'titre' => 'Disponibilité confirmée',
            'message' => 'Merci, vous avez confirmé votre disponibilité pour cette commande.',
            'success' => true,
        ]);
    }

    public function refuserDisponibilite(Request $request, int $id)
    {
        $commande = Commande::findOrFail($id);
        // Libérer l’assignation
        $commande->livreur_id = null;
        $commande->statut = 'en_attente_livreur';
        $commande->save();
        return view('livreur.validation-result', [
            'titre' => 'Disponibilité refusée',
            'message' => "Vous avez refusé cette commande. L'administrateur sera informé.",
            'success' => false,
        ]);
    }

    /**
     * Mise à jour de la position en temps réel du livreur pour une commande.
     */
    public function mettreAJourPosition(Request $request, int $id)
    {
        $livreur = Auth::guard('livreur')->user();
        $data = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $commande = Commande::where('id', $id)->where('livreur_id', $livreur->id)->firstOrFail();
        $commande->livreur_lat = $data['lat'];
        $commande->livreur_lng = $data['lng'];
        $commande->livreur_last_seen_at = now();
        $commande->save();

        return response()->json([
            'ok' => true,
            'updated_at' => $commande->livreur_last_seen_at?->toIso8601String(),
        ]);
    }
}
