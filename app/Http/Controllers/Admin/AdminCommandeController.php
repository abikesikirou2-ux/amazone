<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Illuminate\Support\Facades\Auth;

class AdminCommandeController extends Controller
{
    private function ensureAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->estAdmin(), 403);
    }

    public function index()
    {
        $this->ensureAdmin();
        $commandes = Commande::with(['user:id,name,email', 'livreur:id,nom,prenom'])
            ->orderByDesc('id')
            ->paginate(20);
        return view('admin.commandes.index', compact('commandes'));
    }
}
