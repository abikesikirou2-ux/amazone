<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReductionGlobale;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminReductionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        // Vous pouvez ajouter un middleware admin si disponible: $this->middleware('can:admin');
    }

    public function index()
    {
        \App\Models\ReductionGlobale::autoExpire();
        $reductions = ReductionGlobale::orderByDesc('date_debut')->paginate(10);
        $active = ReductionGlobale::active()->first();
        return view('admin.reduction.index', compact('reductions', 'active'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after:date_debut',
            'pourcentage' => 'required|numeric|min:0|max:100',
            'actif' => 'nullable|boolean',
        ]);

        // Par défaut actif
        $data['actif'] = $request->boolean('actif', true);

        // Garantir une seule promo active à la fois
        if ($data['actif'] === true) {
            ReductionGlobale::query()->where('actif', true)->update(['actif' => false]);
        }

        ReductionGlobale::create($data);

        return back()->with('success', 'Période de réduction créée avec succès.');
    }

    public function toggle($id)
    {
        $r = ReductionGlobale::findOrFail($id);
        $r->update(['actif' => !$r->actif]);
        return back()->with('success', 'Statut de la réduction mis à jour.');
    }
}
