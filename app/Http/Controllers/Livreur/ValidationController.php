<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidationController extends Controller
{
    public function accept(string $token)
    {
        $livreur = Livreur::where('validation_token', $token)->firstOrFail();
        $livreur->valide = true;
        $livreur->valide_le = now();
        $livreur->validation_token = null; // rendre le lien à usage unique
        $livreur->save();

        return view('livreur.validation-result', [
            'titre' => 'Compte validé',
            'message' => 'Merci ! Votre compte livreur est maintenant activé. Vous pouvez vous connecter.',
            'success' => true,
        ]);
    }

    public function refuse(string $token)
    {
        $livreur = Livreur::where('validation_token', $token)->firstOrFail();
        $livreur->valide = false;
        $livreur->refuse_le = now();
        $livreur->validation_token = null; // invalider le lien
        $livreur->disponible = false; // le rendre indisponible par défaut
        $livreur->save();

        Log::info('Validation compte livreur refusée', ['livreur_id' => $livreur->id, 'email' => $livreur->email]);

        return view('livreur.validation-result', [
            'titre' => 'Validation refusée',
            'message' => "Vous avez refusé la validation de votre compte. L'administrateur en sera informé.",
            'success' => false,
        ]);
    }
}
