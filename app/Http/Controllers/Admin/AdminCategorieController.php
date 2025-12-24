<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCategorieController extends Controller
{
    private function ensureAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->estAdmin(), 403);
    }

    public function index()
    {
        $this->ensureAdmin();
        $categories = Categorie::orderBy('nom')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Categorie::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(int $id)
    {
        $this->ensureAdmin();
        $categorie = Categorie::findOrFail($id);
        return view('admin.categories.edit', compact('categorie'));
    }

    public function update(Request $request, int $id)
    {
        $this->ensureAdmin();
        $categorie = Categorie::findOrFail($id);
        $data = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $categorie->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(int $id)
    {
        $this->ensureAdmin();
        $categorie = Categorie::findOrFail($id);
        $produitsDisponibles = $categorie->produits()->where('actif', true)->where('stock', '>', 0)->count();
        if ($produitsDisponibles > 0) {
            return back()->with('error', "Impossible de supprimer: la catégorie contient {$produitsDisponibles} produit(s) disponible(s).");
        }
        // Par sécurité, bloquer la suppression si des produits existent encore (même inactifs) à cause des contraintes FK
        $produitsTotal = $categorie->produits()->count();
        if ($produitsTotal > 0) {
            return back()->with('error', 'Impossible de supprimer: des produits sont encore liés à cette catégorie. Supprimez ou réaffectez-les.');
        }
        $categorie->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
