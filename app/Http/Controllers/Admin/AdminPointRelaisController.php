<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PointRelais;

class AdminPointRelaisController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        abort_unless($user && method_exists($user, 'estAdmin') && $user->estAdmin(), 403);

        $points = PointRelais::query()->latest()->paginate(20);
        $manquants = PointRelais::query()->where(function($q){
            $q->whereNull('latitude')->orWhereNull('longitude');
        })->count();

        return view('admin.points-relais.index', compact('points', 'manquants'));
    }

    public function geocoder()
    {
        $user = Auth::user();
        abort_unless($user && method_exists($user, 'estAdmin') && $user->estAdmin(), 403);

        $aFaire = PointRelais::query()
            ->where(function($q){
                $q->whereNull('latitude')->orWhereNull('longitude');
            })
            ->actif()
            ->get();

        $ok = 0; $ko = 0;
        foreach ($aFaire as $pr) {
            try {
                $coords = PointRelais::geocoderAdresse($pr->adresse, $pr->code_postal, $pr->ville, $pr->nom);
                if ($coords) {
                    [$lat, $lng] = $coords;
                    $pr->latitude = $lat;
                    $pr->longitude = $lng;
                    $pr->save();
                    $ok++;
                } else {
                    $ko++;
                }
            } catch (\Throwable $e) {
                $ko++;
            }
        }

        return back()->with('success', "Géocodage terminé: {$ok} mis à jour, {$ko} non résolus.");
    }
}
