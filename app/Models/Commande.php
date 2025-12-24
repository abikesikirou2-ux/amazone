<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_commande',
        'user_id',
        'sous_total',
        'prix_livraison',
        'reduction',
        'total',
        'statut',
        'mode_livraison_id',
        'adresse_livraison',
        'ville_livraison',
        'quartier_livraison',
        'point_relais_id',
        'livreur_id',
        'coupon_id',
        'numero_suivi',
        'qr_token',
        'recu_le',
    ];

    protected $casts = [
        'sous_total' => 'decimal:2',
        'prix_livraison' => 'decimal:2',
        'reduction' => 'decimal:2',
        'total' => 'decimal:2',
        'recu_le' => 'datetime',
        'livreur_lat' => 'float',
        'livreur_lng' => 'float',
        'livreur_last_seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->hasMany(ArticleCommande::class, 'commande_id');
    }

    public function modeLivraison()
    {
        return $this->belongsTo(ModeLivraison::class);
    }

    public function pointRelais()
    {
        return $this->belongsTo(PointRelais::class);
    }

    public function livreur()
    {
        return $this->belongsTo(Livreur::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'commande_id');
    }

    public static function genererNumeroCommande()
    {
        return 'CMD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function calculerTotal()
    {
        $this->total = $this->sous_total + $this->prix_livraison - $this->reduction;
        return $this->total;
    }

    public function assurerQrToken(): string
    {
        if (empty($this->qr_token)) {
            $this->qr_token = Str::uuid()->toString();
            $this->save();
        }
        return $this->qr_token;
    }

    public function lienConfirmationReception(): string
    {
        $token = $this->assurerQrToken();
        return url('/commande/reception/' . $token);
    }
}
