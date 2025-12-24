<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use App\Models\ReductionGlobale;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function index()
    {
        \App\Models\ReductionGlobale::autoExpire();
        $categories = Categorie::withCount('produits')->get();
        $produits = Produit::with('categorie')
            ->actif()
            ->take(8)
            ->get();

        // Réduction globale active (promo en cours)
        $reductionActive = null;
        try {
            $reductionActive = ReductionGlobale::active()->first();
        } catch (\Throwable $e) {
            // Table non migrée: ignorer
        }

        return view('accueil', compact('categories', 'produits', 'reductionActive'));
    }
}
