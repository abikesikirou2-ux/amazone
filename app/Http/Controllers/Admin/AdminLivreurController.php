<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LivreurCompteCree;
use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminLivreurController extends Controller
{
    private function ensureAdmin(): void
    {
        $user = Auth::user();
        abort_unless($user && $user->estAdmin(), 403);
    }

    public function index()
    {
        $this->ensureAdmin();
        $livreurs = Livreur::orderBy('nom')->paginate(20);
        return view('admin.livreurs.index', compact('livreurs'));
    }

    public function toggleDisponibilite(Request $request, int $id)
    {
        $this->ensureAdmin();
        $l = Livreur::findOrFail($id);
        $l->disponible = !$l->disponible;
        $l->save();
        return back()->with('success', 'Disponibilité mise à jour.');
    }

    public function create()
    {
        $this->ensureAdmin();
        return view('admin.livreurs.create');
    }

    public function store(Request $request)
    {
        $this->ensureAdmin();
        $data = $request->validate([
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc,dns', 'max:150', 'unique:livreurs,email'],
            'telephone' => ['required', 'string', 'max:30'],
            'ville' => ['required', 'string', 'max:100'],
            'quartier' => ['nullable', 'string', 'max:100'],
            'disponible' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'email.required' => "L'adresse e-mail est obligatoire.",
            'email.email' => "L'adresse e-mail n'est pas valide.",
            'email.unique' => "Cette adresse e-mail est déjà utilisée.",
            'telephone.required' => 'Le téléphone est obligatoire.',
            'ville.required' => 'La ville est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caractères.',
        ]);

        $data['disponible'] = (bool)($data['disponible'] ?? true);
        // Validation par email: initialement non validé + token unique
        $data['valide'] = false;
        $data['validation_token'] = Str::random(64);
        $plainPassword = $data['password'] ?? str()->random(10);
        $data['password'] = Hash::make($plainPassword);

            $livreur = Livreur::create($data);

            // Envoi email (queue ou direct selon config)
            try {
                $m = new LivreurCompteCree($livreur, $plainPassword);
                if (config('queue.default') === 'sync') {
                    Mail::to($livreur->email)->send($m);
                } else {
                    Mail::to($livreur->email)->queue($m);
                }
                $livreur->validation_envoye_le = now();
                $livreur->save();
            } catch (\Throwable $e) {
                // En cas d'environnement non configuré pour l'email, on ne bloque pas l'opération, mais on informe l'admin
                \Log::warning('Email livreur non envoyé: ' . $e->getMessage());
                return redirect()->route('admin.livreurs.index')
                    ->with('error', "Livreur ajouté, mais l'email n'a pas pu être envoyé. Vérifiez la configuration MAIL_* et la queue.");
            }

        return redirect()->route('admin.livreurs.index')
            ->with('success', 'Livreur ajouté avec succès.');
    }

    public function destroy(Request $request, int $id)
    {
        $this->ensureAdmin();
        $livreur = Livreur::findOrFail($id);
        try {
            $email = $livreur->email;
            $livreur->delete();
            return back()->with('success', "Livreur $email supprimé.");
        } catch (\Throwable $e) {
            \Log::error('Suppression livreur échouée: ' . $e->getMessage());
            return back()->with('error', "Impossible de supprimer ce livreur.");
        }
    }

    public function renvoyerValidation(Request $request, int $id)
    {
        $this->ensureAdmin();
        $livreur = Livreur::findOrFail($id);

        if ($livreur->valide) {
            return back()->with('success', 'Ce compte livreur est déjà validé.');
        }

        if (empty($livreur->validation_token)) {
            $livreur->validation_token = Str::random(64);
            $livreur->save();
        }
        // Anti-spam: limiter à un envoi toutes les 10 minutes (message lisible)
        if ($livreur->validation_envoye_le) {
            $cooldownMinutes = 10;
            $elapsedSeconds = $livreur->validation_envoye_le->diffInSeconds(now());
            $remainingSeconds = ($cooldownMinutes * 60) - $elapsedSeconds;
            if ($remainingSeconds > 0) {
                if ($remainingSeconds >= 60) {
                    $restant = ceil($remainingSeconds / 60) . ' minute(s)';
                } else {
                    $restant = $remainingSeconds . ' seconde(s)';
                }
                return back()->with('error', "E-mail déjà envoyé récemment. Réessayez dans $restant.");
            }
        }

        try {
            $m = new LivreurCompteCree($livreur, null);
            if (config('queue.default') === 'sync') {
                Mail::to($livreur->email)->send($m);
            } else {
                Mail::to($livreur->email)->queue($m);
            }
            $livreur->validation_envoye_le = now();
            $livreur->save();
        } catch (\Throwable $e) {
            \Log::warning('Renvoyer validation - email non envoyé: ' . $e->getMessage());
            return back()->with('error', "Impossible d'envoyer l'email de validation. Vérifiez MAIL_* et lancez la queue si nécessaire.");
        }

        return back()->with('success', "Email de validation renvoyé à {$livreur->email}.");
    }

    public function renvoyerValidationForce(Request $request, int $id)
    {
        $this->ensureAdmin();
        $livreur = Livreur::findOrFail($id);

        if ($livreur->valide) {
            return back()->with('success', 'Ce compte livreur est déjà validé.');
        }

        if (empty($livreur->validation_token)) {
            $livreur->validation_token = Str::random(64);
            $livreur->save();
        }

        try {
            $m = new LivreurCompteCree($livreur, null);
            if (config('queue.default') === 'sync') {
                Mail::to($livreur->email)->send($m);
            } else {
                Mail::to($livreur->email)->queue($m);
            }
            $livreur->validation_envoye_le = now();
            $livreur->save();
        } catch (\Throwable $e) {
            \Log::warning('Renvoyer validation (force) - email non envoyé: ' . $e->getMessage());
            return back()->with('error', "Impossible d'envoyer l'email de validation (forcé). Vérifiez MAIL_* et la queue.");
        }

        return back()->with('success', "Email de validation (forcé) envoyé à {$livreur->email}.");
    }
}
