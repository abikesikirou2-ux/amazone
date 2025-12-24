<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\ArticleCommande;
use App\Models\ModeLivraison;
use App\Models\PointRelais;
use App\Models\Livreur;
use App\Models\Coupon;
use App\Models\MouvementStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\ReductionGlobale;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\CouponFidelite;
use App\Mail\AdminNotificationCoupon;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use App\Mail\DemandeDisponibiliteLivreur;
use App\Mail\AdminCommandeLancee;

class CommandeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $commandes = Auth::user()->commandes()
            ->with(['articles.produit', 'modeLivraison'])
            ->latest()
            ->paginate(10);

        return view('commandes.index', compact('commandes'));
    }

    public function show($id)
    {
        $commande = Commande::with(['articles.produit', 'modeLivraison', 'pointRelais', 'livreur'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('commandes.show', compact('commande'));
    }

    public function creer()
    {
        $panier = Auth::user()->panier;
        
        if (!$panier || $panier->articles->isEmpty()) {
            return redirect()->route('panier')->with('error', 'Votre panier est vide.');
        }

        $modesLivraison = ModeLivraison::actif()->get();
        $adresses = Auth::user()->adressesLivraison;

        return view('commandes.creer', compact('modesLivraison', 'adresses', 'panier'));
    }

    public function rechercherPointsRelais(Request $request)
    {
        $request->validate([
            'code_postal' => 'required|string|max:10'
        ]);

        $points = PointRelais::rechercherParCodePostal($request->code_postal);

        return response()->json($points);
    }

    public function enregistrer(Request $request)
    {
        $request->validate([
            'mode_livraison_id' => 'required|exists:modes_livraison,id',
            'adresse_livraison' => 'required_if:type_livraison,domicile',
            'ville_livraison' => 'required_if:type_livraison,domicile',
            'quartier_livraison' => 'nullable|string',
            'point_relais_id' => 'required_if:type_livraison,relais|exists:points_relais,id',
            'coupon_code' => 'nullable|string',
        ]);

        $panier = Auth::user()->panier;
        
        if (!$panier || $panier->articles->isEmpty()) {
            return redirect()->route('panier')->with('error', 'Votre panier est vide.');
        }

        DB::beginTransaction();
        
        try {
            $sousTotal = $panier->obtenirTotal();
            $modeLivraison = ModeLivraison::findOrFail($request->mode_livraison_id);
            $prixLivraison = $modeLivraison->prix;
            $reduction = 0;
            $couponId = null;
            $couponValide = false;

            // Vérifier d'abord le coupon (mutuellement exclusif avec réduction globale)
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', $request->coupon_code)->first();
                if ($coupon && $coupon->valider($sousTotal, Auth::id())) {
                    $reduction = $coupon->calculerReduction($sousTotal);
                    $couponId = $coupon->id;
                    $couponValide = true;
                    if ($coupon->livraison_gratuite) {
                        $prixLivraison = 0;
                    }
                }
            }

            // Si aucun coupon valide, appliquer la réduction globale active
            if (!$couponValide) {
                try {
                    if ($promo = ReductionGlobale::active()->first()) {
                        $reduction = $promo->calculerReduction((float)$sousTotal);
                    }
                } catch (\Throwable $e) {
                    // En phase d'installation/migration, ignorer les erreurs
                }
            }

            // Créer la commande
            $commande = Commande::create([
                'numero_commande' => Commande::genererNumeroCommande(),
                'user_id' => Auth::id(),
                'sous_total' => $sousTotal,
                'prix_livraison' => $prixLivraison,
                'reduction' => $reduction,
                'total' => $sousTotal + $prixLivraison - $reduction,
                'statut' => 'en_attente',
                'mode_livraison_id' => $request->mode_livraison_id,
                'adresse_livraison' => $request->adresse_livraison,
                'ville_livraison' => $request->ville_livraison,
                'quartier_livraison' => $request->quartier_livraison,
                'point_relais_id' => $request->point_relais_id,
                'coupon_id' => $couponId,
            ]);

            // Copier les articles du panier
            foreach ($panier->articles as $article) {
                ArticleCommande::create([
                    'commande_id' => $commande->id,
                    'produit_id' => $article->produit_id,
                    'variante_id' => $article->variante_id,
                    'taille' => $article->taille,
                    'quantite' => $article->quantite,
                    'prix' => $article->prix,
                ]);

                // Diminuer le stock
                $article->produit->diminuerStock($article->quantite);

                // Enregistrer le mouvement de stock
                MouvementStock::enregistrer(
                    $article->produit_id,
                    $article->quantite,
                    'vente',
                    $commande->id,
                    'Commande ' . $commande->numero_commande
                );
            }

            // Incrémenter l'utilisation du coupon
            if ($couponId) {
                Coupon::find($couponId)->incrementerUtilisation();
            }

            // Assigner un livreur si livraison à domicile
            if ($request->type_livraison === 'domicile' && $request->ville_livraison) {
                $livreur = Livreur::trouverDisponible(
                    $request->ville_livraison,
                    $request->quartier_livraison
                );

                if ($livreur) {
                    $commande->update([
                        'livreur_id' => $livreur->id,
                        'statut' => 'en_attente_livreur'
                    ]);

                    // Envoyer email au livreur pour confirmer ou refuser sa disponibilité
                    try {
                        $acceptUrl = URL::signedRoute('livreur.commande.accepter', ['id' => $commande->id]);
                        $refuseUrl = URL::signedRoute('livreur.commande.refuser', ['id' => $commande->id]);
                        Mail::to($livreur->email)->send(new DemandeDisponibiliteLivreur($commande, $livreur, $acceptUrl, $refuseUrl));
                    } catch (\Throwable $e) {
                        \Log::warning('Email disponibilité livreur non envoyé: ' . $e->getMessage());
                    }
                }
            }

            // Vider le panier
            $panier->articles()->delete();

            DB::commit();

            // Attribution automatique d'un code promo si 10 achats en 12 mois
            try {
                $achats12mois = Auth::user()->commandes()
                    ->where('created_at', '>=', now()->subYear())
                    ->count();

                // Attribuer si le client atteint au moins 10 achats et n'a pas déjà un coupon actif attribué
                if ($achats12mois >= 10) {
                    // Limite: un coupon fidélité max par année civile pour ce client
                    $dejaAttribue = Coupon::query()
                        ->where('user_id', Auth::id())
                        ->whereYear('date_debut', now()->year)
                        ->exists();

                    if (!$dejaAttribue) {
                        $code = strtoupper(Str::random(10));
                        $newCoupon = Coupon::create([
                            'code' => $code,
                            'type' => 'pourcentage',
                            'valeur' => 10,
                            'montant_minimum' => 0,
                            'livraison_gratuite' => false,
                            'date_debut' => now(),
                            'date_fin' => now()->addMonths(2),
                            'utilisations_max' => 1,
                            'compteur_utilisation' => 0,
                            'actif' => true,
                            'user_id' => Auth::id(),
                        ]);

                        // Email au client avec le code et la date d'expiration via Mailable
                        try {
                            Mail::to(Auth::user()->email)->send(new CouponFidelite(Auth::user(), $newCoupon));
                        } catch (\\Throwable $e) {
                            \Log::error('Envoi email coupon auto échoué: ' . $e->getMessage());
                        }

                        // Informer les administrateurs: liste mise à jour avec ce nouveau code
                        try {
                            $admins = User::query()->where('role', 'admin')->pluck('email')->all();
                            if (!empty($admins)) {
                                foreach ($admins as $adminEmail) {
                                    Mail::to($adminEmail)->send(new AdminNotificationCoupon(Auth::user(), $newCoupon));
                                }
                            }
                        } catch (\\Throwable $e) {
                            \\Log::error('Notification admin coupon auto échouée: ' . $e->getMessage());
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Log::error('Attribution auto coupon erreur: ' . $e->getMessage());
            }

            return redirect()->route('commande.confirmation', $commande->id)
                ->with('success', 'Commande créée avec succès!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue: ' . $e->getMessage());
        }
    }

    public function confirmation($id)
    {
        $commande = Commande::with(['articles.produit', 'modeLivraison', 'livreur'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('commandes.confirmation', compact('commande'));
    }

    public function payer(int $id)
    {
        $commande = Commande::with(['articles.produit', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Simuler paiement: statut et horodatage
        $commande->statut = 'payee';
        $commande->recu_le = now();
        $commande->save();

        // Générer PDF du reçu
        $html = view('commandes.recu', compact('commande'))->render();
        $dompdf = new Dompdf([ 'isRemoteEnabled' => true ]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        $pdfOutput = $dompdf->output();

        $fileName = 'recu_' . $commande->numero_commande . '.pdf';
        $path = 'recu/' . $fileName;
        Storage::disk('local')->put($path, $pdfOutput);

        // Envoi notification admin (mail) qu'une commande est lancée/payée
        try {
            $admins = User::query()->where('role', 'admin')->pluck('email')->all();
            foreach ($admins as $adminEmail) {
                Mail::to($adminEmail)->send(new AdminCommandeLancee($commande));
            }
        } catch (\Throwable $e) {
            \Log::warning('Notification admin commande lancée non envoyée: ' . $e->getMessage());
        }

        // Télécharger automatiquement
        return response()->download(storage_path('app/' . $path), $fileName);
    }

    public function scanner()
    {
        // Affiche la page scanner QR côté client
        return view('commandes.scanner');
    }

    /**
     * Position du livreur pour une commande (vue client, polling).
     */
    public function position(int $id)
    {
        $commande = Commande::select(['id','user_id','livreur_lat','livreur_lng','livreur_last_seen_at'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'lat' => $commande->livreur_lat,
            'lng' => $commande->livreur_lng,
            'last_seen_at' => optional($commande->livreur_last_seen_at)->toIso8601String(),
        ]);
    }
}
