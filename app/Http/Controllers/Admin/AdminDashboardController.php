<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    private function ensureAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->estAdmin(), 403);
    }

    public function index()
    {
        $this->ensureAdmin();

        $totalVentes = (float) (Commande::sum('total') ?? 0);
        $nbCommandes = (int) Commande::count();
        $panierMoyen = $nbCommandes > 0 ? $totalVentes / $nbCommandes : 0.0;
        $ventesAujourdHui = (float) (Commande::whereDate('created_at', Carbon::today())->sum('total') ?? 0);

        // Ventes sur les 7 derniers jours (sparkline)
        $jours = collect(range(6, 0))->map(function ($i) {
            $date = Carbon::today()->subDays($i);
            return [
                'date' => $date->format('Y-m-d'),
                'label' => $date->translatedFormat('d M'),
            ];
        });

        $totauxParJour = Commande::selectRaw('DATE(created_at) as d, SUM(total) as s')
            ->whereDate('created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('d')
            ->pluck('s', 'd');

        $serie7j = $jours->map(function ($j) use ($totauxParJour) {
            return [
                'label' => $j['label'],
                'total' => (float) ($totauxParJour[$j['date']] ?? 0),
            ];
        })->values();

        // Répartition par statut (si présent)
        $parStatut = Commande::selectRaw('COALESCE(statut, "inconnu") as s, COUNT(*) as c')
            ->groupBy('s')
            ->pluck('c', 's');

        // Dernières ventes
        $dernieresCommandes = Commande::with(['user:id,name,email'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'totalVentes',
            'nbCommandes',
            'panierMoyen',
            'ventesAujourdHui',
            'serie7j',
            'parStatut',
            'dernieresCommandes'
        ));
    }
}
