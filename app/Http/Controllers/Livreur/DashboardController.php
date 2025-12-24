<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $livreur = Auth::guard('livreur')->user();
        $query = Commande::where('livreur_id', $livreur->id)
            ->with(['user:id,name,email'])
            ->orderByDesc('id');

        $filtre = request('statut');
        if ($filtre) {
            $query->where('statut', $filtre);
        }

        $commandes = $query->paginate(15)->withQueryString();

        $compteParStatut = Commande::selectRaw('COALESCE(statut, "inconnu") as s, COUNT(*) as c')
            ->where('livreur_id', $livreur->id)
            ->groupBy('s')
            ->pluck('c', 's');

        $totalLivraisons = $commandes->total();

        return view('livreur.dashboard', compact('livreur', 'commandes', 'totalLivraisons', 'compteParStatut', 'filtre'));
    }

    public function profil()
    {
        $livreur = Auth::guard('livreur')->user();
        return view('livreur.profil', compact('livreur'));
    }
}
