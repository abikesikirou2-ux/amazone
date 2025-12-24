<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Categorie;
use Illuminate\Http\Request;

class ProduitController extends Controller
{
    public function index(Request $request)
    {
        $query = Produit::with('categorie')->actif();

        // Filtrage par catégorie (par identifiant dorénavant)
        $catId = $request->get('categorie') ?? $request->route('id');
        if ($catId) {
            $query->where('categorie_id', (int)$catId);
        }

        // Filtrage segment (F/H/Enfant) pour Mode & accessoires
        if ($request->has('segment') && $request->segment) {
            $query->where('segment', $request->segment);
        }

        // Recherche
        if ($request->has('q') && $request->q) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Tri
        $sort = $request->get('sort', 'recent');
        switch ($sort) {
            case 'prix_asc':
                $query->orderBy('prix', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('prix', 'desc');
                break;
            case 'nom':
                $query->orderBy('nom', 'asc');
                break;
            default:
                $query->latest();
        }

        $produits = $query->paginate(12);
        $categories = Categorie::all();

        return view('produits.index', compact('produits', 'categories'));
    }

    public function show($id)
    {
        $produit = Produit::with(['categorie', 'avis.user', 'images', 'variantes'])->findOrFail($id);
        $produitsLies = Produit::where('categorie_id', $produit->categorie_id)
            ->where('id', '!=', $produit->id)
            ->actif()
            ->take(4)
            ->get();

        return view('produits.show', compact('produit', 'produitsLies'));
    }

    // API: suggestions de produits pour l'autocomplétion
    public function suggestions(Request $request)
    {
        $term = trim((string)$request->get('term', ''));
        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $items = Produit::actif()
            ->with(['categorie:id,nom'])
            ->where('nom', 'like', '%' . $term . '%')
            ->orderBy('nom')
            ->limit(10)
            ->get(['id', 'nom', 'categorie_id']);

        $payload = $items->map(function ($p) {
            return [
                'id' => $p->id,
                'nom' => $p->nom,
                'categorie' => $p->categorie ? [
                    'id' => $p->categorie->id,
                    'nom' => $p->categorie->nom,
                ] : null,
            ];
        });

        return response()->json($payload);
    }
}
