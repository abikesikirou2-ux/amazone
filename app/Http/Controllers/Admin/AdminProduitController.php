<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produit;
use App\Models\Categorie;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminProduitController extends Controller
{
    private function ensureAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->estAdmin(), 403);
    }

    public function index()
    {
        $this->ensureAdmin();
        $produits = Produit::with('categorie')->orderByDesc('id')->paginate(20);
        $categories = Categorie::orderBy('nom')->get();
        return view('admin.produits.index', compact('produits', 'categories'));
    }

    public function create()
    {
        $this->ensureAdmin();
        $categories = Categorie::orderBy('nom')->get();
        return view('admin.produits.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'segment' => 'nullable|in:femme,homme,enfant',
            'stock' => 'required|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);
        $data['actif'] = $request->boolean('actif');

        $produit = Produit::create($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit créé.');
    }

    public function edit(int $id)
    {
        $this->ensureAdmin();
        $produit = Produit::findOrFail($id);
        $categories = Categorie::orderBy('nom')->get();
        return view('admin.produits.edit', compact('produit', 'categories'));
    }

    public function update(Request $request, int $id)
    {
        $this->ensureAdmin();
        $produit = Produit::findOrFail($id);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'segment' => 'nullable|in:femme,homme,enfant',
            'stock' => 'required|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);
        $data['actif'] = $request->boolean('actif');

        $produit->update($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(int $id)
    {
        $this->ensureAdmin();
        $p = Produit::findOrFail($id);
        $p->delete();
        return back()->with('success', 'Produit supprimé.');
    }

    public function toggleActif(Request $request, int $id)
    {
        $this->ensureAdmin();
        $p = Produit::findOrFail($id);
        $p->actif = !$p->actif;
        $p->save();
        return back()->with('success', 'Statut d\'activation mis à jour.');
    }

    public function updateStock(Request $request, int $id)
    {
        $this->ensureAdmin();
        $p = Produit::findOrFail($id);
        $validated = $request->validate([
            'type' => 'required|in:ajout,retrait',
            'quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $q = (int)$validated['quantite'];
        if ($validated['type'] === 'retrait') {
            if ($p->stock < $q) {
                return back()->with('error', 'Stock insuffisant pour retrait.');
            }
            $p->decrement('stock', $q);
        } else {
            $p->increment('stock', $q);
        }

        $typeDb = $validated['type'] === 'retrait' ? 'sortie' : 'entree';
        MouvementStock::enregistrer($p->id, $q, $typeDb, null, $validated['notes'] ?? null);
        return back()->with('success', 'Stock mis à jour.');
    }
}
