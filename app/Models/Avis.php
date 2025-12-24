<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avis extends Model
{
    protected $table = 'avis';

    protected $fillable = [
        'produit_id',
        'user_id',
        'commande_id',
        'note',
        'commentaire',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function estAchatVerifie()
    {
        return $this->commande && $this->commande->statut === 'livree';
    }
}
