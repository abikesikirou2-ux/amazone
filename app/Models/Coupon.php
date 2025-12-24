<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'valeur',
        'montant_minimum',
        'livraison_gratuite',
        'date_debut',
        'date_fin',
        'utilisations_max',
        'compteur_utilisation',
        'actif',
    ];

    protected $casts = [
        'valeur' => 'decimal:2',
        'montant_minimum' => 'decimal:2',
        'livraison_gratuite' => 'boolean',
        'actif' => 'boolean',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function valider($montant = 0, $userId = null)
    {
        if (!$this->actif) {
            return false;
        }

        if (now()->lt($this->date_debut) || now()->gt($this->date_fin)) {
            return false;
        }

        if ($this->utilisations_max && $this->compteur_utilisation >= $this->utilisations_max) {
            return false;
        }

        if ($montant < $this->montant_minimum) {
            return false;
        }

        // Si le coupon est attribué à un client spécifique, ne l'autoriser que pour lui
        if (!empty($this->user_id)) {
            $uid = $userId ?? (Auth::check() ? Auth::id() : null);
            if ($uid === null || (int)$uid !== (int)$this->user_id) {
                return false;
            }
        }

        return true;
    }

    public function calculerReduction($montant)
    {
        if (!$this->valider($montant)) {
            return 0;
        }

        if ($this->type === 'pourcentage') {
            return ($montant * $this->valeur) / 100;
        }

        return $this->valeur;
    }

    public function incrementerUtilisation()
    {
        $this->increment('compteur_utilisation');
    }
}
