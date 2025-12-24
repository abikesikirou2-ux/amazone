<?php

namespace App\Http\Controllers;

use App\Models\Panier;
use App\Models\ArticlePanier;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PanierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $panier = Auth::user()->panier;
        
        if (!$panier) {
            return view('panier.index', ['articles' => collect(), 'total' => 0]);
        }

        $articles = $panier->articles()->with('produit')->get();
        $total = $panier->obtenirTotal();

        return view('panier.index', compact('articles', 'total'));
    }

    public function ajouter(Request $request)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite' => 'integer|min:1',
            'variante_id' => 'nullable|exists:produit_variantes,id'
        ]);

        $produit = Produit::findOrFail($request->produit_id);
        $quantite = $request->input('quantite', 1);
        $varianteId = $request->input('variante_id');
        $taille = null;
        $prixUnitaire = $produit->prix;

        if ($varianteId) {
            $variante = $produit->variantes()->where('id', $varianteId)->first();
            if (!$variante) {
                return back()->with('error', 'Variante invalide pour ce produit.');
            }
            $taille = $variante->taille;
            $prixUnitaire = $variante->prix;
        } else {
            // Si produit de la catégorie Mode & accessoires avec variantes, exiger la sélection d'une taille
            if ($produit->categorie && \Illuminate\Support\Str::contains(\Illuminate\Support\Str::lower($produit->categorie->nom), 'mode') && $produit->variantes()->exists()) {
                return back()->with('error', 'Veuillez sélectionner une taille.');
            }
        }

        if (!$produit->verifierStock($quantite)) {
            return back()->with('error', 'Stock insuffisant pour ce produit.');
        }

        // Créer le panier si nécessaire
        $panier = Auth::user()->panier;
        if (!$panier) {
            $panier = Panier::create(['user_id' => Auth::id()]);
        }

        // Vérifier si le produit existe déjà dans le panier
        $query = ArticlePanier::where('panier_id', $panier->id)
            ->where('produit_id', $produit->id);
        if ($varianteId) {
            $query->where('variante_id', $varianteId);
        } else {
            $query->whereNull('variante_id');
        }
        $article = $query->first();

        if ($article) {
            $nouvelleQuantite = $article->quantite + $quantite;
            
            if (!$produit->verifierStock($nouvelleQuantite)) {
                return back()->with('error', 'Stock insuffisant pour cette quantité.');
            }
            
            $article->update(['quantite' => $nouvelleQuantite]);
        } else {
            ArticlePanier::create([
                'panier_id' => $panier->id,
                'produit_id' => $produit->id,
                'variante_id' => $varianteId,
                'taille' => $taille,
                'quantite' => $quantite,
                'prix' => $prixUnitaire,
            ]);
        }

        return back()->with('success', 'Produit ajouté au panier!');
    }

    public function mettreAJour(Request $request, $id)
    {
        $request->validate([
            'quantite' => 'required|integer|min:1'
        ]);

        $article = ArticlePanier::findOrFail($id);
        
        if ($article->panier->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$article->produit->verifierStock($request->quantite)) {
            return back()->with('error', 'Stock insuffisant pour cette quantité.');
        }

        $article->update(['quantite' => $request->quantite]);

        return back()->with('success', 'Quantité mise à jour!');
    }

    public function supprimer($id)
    {
        $article = ArticlePanier::findOrFail($id);
        
        if ($article->panier->user_id !== Auth::id()) {
            abort(403);
        }

        $article->delete();

        return back()->with('success', 'Article retiré du panier!');
    }

    public function vider()
    {
        $panier = Auth::user()->panier;
        
        if ($panier) {
            $panier->articles()->delete();
        }

        return back()->with('success', 'Panier vidé!');
    }
}
