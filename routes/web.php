<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PanierController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ProfileController;

// Accueil
Route::get('/', [AccueilController::class, 'index'])->name('accueil');
// Alias vers accueil
Route::get('/home', function () {
    return redirect()->route('accueil');
})->name('home');

// Alias FR pour auth (au cas où l'utilisateur tape /connexion ou /inscription)
Route::get('/connexion', function () {
    return redirect()->route('login');
})->name('connexion');
Route::get('/inscription', function () {
    return redirect()->route('register');
})->name('inscription');

// Produits
Route::get('/produits', [ProduitController::class, 'index'])->name('produits');
Route::get('/produit/{id}', [ProduitController::class, 'show'])->name('produit.details');
Route::get('/recherche', [ProduitController::class, 'index'])->name('recherche');
// API suggestions de recherche
Route::get('/api/suggestions', [ProduitController::class, 'suggestions'])->name('api.suggestions');

// Catégories
Route::get('/categories', function () {
    return view('categories.index');
})->name('categories');
Route::get('/categorie/{id}', [ProduitController::class, 'index'])->name('categorie.produits');

// Panier (authentification requise)
Route::middleware(['auth'])->group(function () {
    Route::get('/panier', [PanierController::class, 'index'])->name('panier');
    Route::post('/panier/ajouter', [PanierController::class, 'ajouter'])->name('panier.ajouter');
    Route::patch('/panier/{id}', [PanierController::class, 'mettreAJour'])->name('panier.update');
    Route::delete('/panier/{id}', [PanierController::class, 'supprimer'])->name('panier.supprimer');
    Route::delete('/panier', [PanierController::class, 'vider'])->name('panier.vider');

    // Commandes
    Route::get('/commandes', [CommandeController::class, 'index'])->name('commandes');
    Route::get('/commande/creer', [CommandeController::class, 'creer'])->name('commande.creer');
    Route::post('/commande', [CommandeController::class, 'enregistrer'])->name('commande.enregistrer');
    Route::get('/commande/{id}', [CommandeController::class, 'show'])->name('commande.show');
    Route::get('/commande/{id}/confirmation', [CommandeController::class, 'confirmation'])->name('commande.confirmation');
    Route::post('/commande/{id}/payer', [CommandeController::class, 'payer'])->name('commande.payer');
    Route::get('/commande/scanner', [CommandeController::class, 'scanner'])->name('commande.scanner');
    Route::get('/commande/{id}/position', [CommandeController::class, 'position'])->name('commande.position');
    Route::post('/api/points-relais', [CommandeController::class, 'rechercherPointsRelais'])->name('api.points-relais');

        // Réductions globales (bonus)
        Route::get('/admin/reduction', [\App\Http\Controllers\Admin\AdminReductionController::class, 'index'])->name('admin.reduction.index');
        Route::post('/admin/reduction', [\App\Http\Controllers\Admin\AdminReductionController::class, 'store'])->name('admin.reduction.store');
        Route::post('/admin/reduction/{id}/toggle', [\App\Http\Controllers\Admin\AdminReductionController::class, 'toggle'])->name('admin.reduction.toggle');
    // Compte utilisateur
    Route::get('/compte', function () {
        return view('compte.index');
    })->name('compte');
});

// Confirmation de réception via QR (publique)
Route::get('/commande/reception/{token}', [\App\Http\Controllers\CommandeReceptionController::class, 'confirmer'])->name('commande.reception.confirmer');

// Pages statiques
Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'nom' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email:rfc,dns', 'max:255'],
        'sujet' => ['required', 'string', 'max:255'],
        'message' => ['required', 'string', 'min:10'],
    ]);

    try {
        $to = 'mimiamazone555@gmail.com';
        $subject = 'Contact Mini Amazon: ' . $data['sujet'];
        \Illuminate\Support\Facades\Mail::raw(
            "De: {$data['nom']} <{$data['email']}>::\n\n" . $data['message'],
            function ($m) use ($to, $subject) {
                $m->to($to)->subject($subject);
            }
        );
        return back()->with('success', 'Votre message a été envoyé. Merci !');
    } catch (\Throwable $e) {
        \Log::error('Contact form error: ' . $e->getMessage());
        return back()->with('error', "Impossible d'envoyer le message pour le moment. Réessayez plus tard.");
    }
})->name('contact.envoyer');

Route::get('/promos', function () {
    return view('pages.promos');
})->name('promos');

Route::get('/nouveautes', function () {
    return view('pages.nouveautes');
})->name('nouveautes');

Route::get('/aide', function () {
    return view('pages.aide');
})->name('aide');

Route::get('/livraison', function () {
    return view('pages.livraison');
})->name('livraison');

Route::get('/retours', function () {
    return view('pages.retours');
})->name('retours');

Route::get('/cgv', function () {
    return view('pages.cgv');
})->name('cgv');

// Newsletter
Route::post('/newsletter/subscribe', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'email' => 'required|email:rfc,dns|max:255',
    ]);

    \App\Models\InscriptionNewsletter::firstOrCreate(['email' => $validated['email']]);

    return back()->with('success', 'Merci de votre inscription!');
})->name('newsletter.subscribe');

// Admin: liste des inscriptions newsletter
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/newsletter', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        abort_unless($user && $user->estAdmin(), 403);
        $inscriptions = \App\Models\InscriptionNewsletter::latest()->paginate(20);
        return view('admin.newsletter.index', compact('inscriptions'));
    })->name('admin.newsletter.index');
});

// Breeze dashboard & profile (facultatif)
Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();
    // Admins accèdent sans vérification et sont redirigés vers l'espace admin
    if ($user && (method_exists($user, 'estAdmin') ? $user->estAdmin() : false)) {
        return redirect()->route('admin.dashboard');
    }
    // Clients non vérifiés: redirection vers l'avis de vérification
    if ($user && method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// Auth Livreur
Route::prefix('livreur')->group(function () {
    // Validation de compte par email (public)
    Route::get('/validation/accept/{token}', [\App\Http\Controllers\Livreur\ValidationController::class, 'accept'])->name('livreur.validation.accept');
    Route::get('/validation/refuse/{token}', [\App\Http\Controllers\Livreur\ValidationController::class, 'refuse'])->name('livreur.validation.refuse');

    Route::middleware('guest:livreur')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Livreur\AuthController::class, 'showLogin'])->name('livreur.login');
        Route::post('/login', [\App\Http\Controllers\Livreur\AuthController::class, 'login'])->name('livreur.login.submit');
    });

    Route::middleware('auth:livreur')->group(function () {
        Route::post('/logout', [\App\Http\Controllers\Livreur\AuthController::class, 'logout'])->name('livreur.logout');
        Route::get('/', [\App\Http\Controllers\Livreur\DashboardController::class, 'index'])->name('livreur.dashboard');
        Route::get('/profil', [\App\Http\Controllers\Livreur\DashboardController::class, 'profil'])->name('livreur.profil');
        Route::post('/profil/mot-de-passe', [\App\Http\Controllers\Livreur\PasswordController::class, 'update'])->name('livreur.password.update');
        Route::get('/commande/{id}/qr', [\App\Http\Controllers\Livreur\CommandeController::class, 'qr'])->name('livreur.commande.qr');
        Route::post('/commande/{id}/livrer', [\App\Http\Controllers\Livreur\CommandeController::class, 'livrer'])->name('livreur.commande.livrer');
        Route::post('/commande/{id}/position', [\App\Http\Controllers\Livreur\CommandeController::class, 'mettreAJourPosition'])->name('livreur.commande.position');
    });
});

// Liens signés pour disponibilité livreur (sans authentification)
Route::get('/livreur/commande/{id}/accepter', [\App\Http\Controllers\Livreur\CommandeController::class, 'accepterDisponibilite'])
    ->middleware('signed')
    ->name('livreur.commande.accepter');
Route::get('/livreur/commande/{id}/refuser', [\App\Http\Controllers\Livreur\CommandeController::class, 'refuserDisponibilite'])
    ->middleware('signed')
    ->name('livreur.commande.refuser');

// Admin sur-mesure (CRUD Produits/Catégories)
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard admin
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Produits
    Route::get('/produits', [\App\Http\Controllers\Admin\AdminProduitController::class, 'index'])->name('admin.produits.index');
    Route::get('/produits/creer', [\App\Http\Controllers\Admin\AdminProduitController::class, 'create'])->name('admin.produits.create');
    Route::post('/produits', [\App\Http\Controllers\Admin\AdminProduitController::class, 'store'])->name('admin.produits.store');
    Route::get('/produits/{id}/edit', [\App\Http\Controllers\Admin\AdminProduitController::class, 'edit'])->name('admin.produits.edit');
    Route::patch('/produits/{id}', [\App\Http\Controllers\Admin\AdminProduitController::class, 'update'])->name('admin.produits.update');
    Route::delete('/produits/{id}', [\App\Http\Controllers\Admin\AdminProduitController::class, 'destroy'])->name('admin.produits.destroy');
    Route::post('/produits/{id}/actif', [\App\Http\Controllers\Admin\AdminProduitController::class, 'toggleActif'])->name('admin.produits.toggleActif');
    Route::post('/produits/{id}/stock', [\App\Http\Controllers\Admin\AdminProduitController::class, 'updateStock'])->name('admin.produits.stock');

    // Catégories
    Route::get('/categories', [\App\Http\Controllers\Admin\AdminCategorieController::class, 'index'])->name('admin.categories.index');
        // Commandes
        Route::get('/commandes', [\App\Http\Controllers\Admin\AdminCommandeController::class, 'index'])->name('admin.commandes.index');

        // Livreurs
        Route::get('/livreurs', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'index'])->name('admin.livreurs.index');
        Route::get('/livreurs/creer', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'create'])->name('admin.livreurs.create');
        Route::post('/livreurs', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'store'])->name('admin.livreurs.store');
        // Suppression d'un livreur
        Route::delete('/livreurs/{id}', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'destroy'])->name('admin.livreurs.destroy');
        Route::post('/livreurs/{id}/renvoyer-validation', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'renvoyerValidation'])->name('admin.livreurs.renvoyer');
        Route::post('/livreurs/{id}/renvoyer-validation/force', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'renvoyerValidationForce'])->name('admin.livreurs.renvoyer.force');
        Route::post('/livreurs/{id}/toggle', [\App\Http\Controllers\Admin\AdminLivreurController::class, 'toggleDisponibilite'])->name('admin.livreurs.toggle');
    Route::get('/categories/creer', [\App\Http\Controllers\Admin\AdminCategorieController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [\App\Http\Controllers\Admin\AdminCategorieController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [\App\Http\Controllers\Admin\AdminCategorieController::class, 'edit'])->name('admin.categories.edit');
    Route::patch('/categories/{id}', [\App\Http\Controllers\Admin\AdminCategorieController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\AdminCategorieController::class, 'destroy'])->name('admin.categories.destroy');

    // Points relais (admin)
    Route::get('/points-relais', [\App\Http\Controllers\Admin\AdminPointRelaisController::class, 'index'])->name('admin.points-relais.index');
    Route::post('/points-relais/geocoder', [\App\Http\Controllers\Admin\AdminPointRelaisController::class, 'geocoder'])->name('admin.points-relais.geocoder');

    // Email test (admin)
    Route::get('/email-test', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        abort_unless($user && method_exists($user, 'estAdmin') && $user->estAdmin(), 403);
        try {
            \Illuminate\Support\Facades\Mail::raw('Ceci est un e-mail de test SMTP pour Mini Amazon.', function ($message) use ($user) {
                $message->to($user->email)->subject('Test SMTP - Mini Amazon');
            });
            return back()->with('success', 'E-mail de test envoyé à ' . $user->email);
        } catch (\Throwable $e) {
            \Log::error('Echec email-test: ' . $e->getMessage());
            return back()->with('error', "Impossible d'envoyer l'e-mail de test: " . $e->getMessage());
        }
    })->name('admin.email.test');

    // Email test: validation de compte client (aperçu du message, même style que livreur)
    Route::get('/email-test-client', function () {
        $user = \Illuminate\Support\Facades\Auth::user();
        abort_unless($user && method_exists($user, 'estAdmin') && $user->estAdmin(), 403);
        try {
            $verificationUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('auth.verification.expire', 60)),
                [
                    'id' => $user->getKey(),
                    'hash' => sha1($user->getEmailForVerification()),
                ]
            );
            \Illuminate\Support\Facades\Mail::to($user->email)
                ->send(new \App\Mail\ClientCompteValidation($user, $verificationUrl));
            return back()->with('success', 'E-mail de validation client envoyé à ' . $user->email);
        } catch (\Throwable $e) {
            \Log::error('Echec email-test-client: ' . $e->getMessage());
            return back()->with('error', "Impossible d'envoyer l'e-mail de validation client: " . $e->getMessage());
        }
    })->name('admin.email.test.client');

    // Coupons attribués aux clients
    Route::get('/coupons', [\App\Http\Controllers\Admin\AdminCouponController::class, 'index'])->name('admin.coupons.index');
    Route::post('/coupons/{id}/toggle', [\App\Http\Controllers\Admin\AdminCouponController::class, 'toggle'])->name('admin.coupons.toggle');
});
