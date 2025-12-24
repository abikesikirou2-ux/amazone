<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCommande extends Model
{
    protected $table = 'articles_commande';

    protected $fillable = [
        'commande_id',
        'produit_id',
        'variante_id',
        'taille',
        'quantite',
        'prix',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function variante()
    {
        return $this->belongsTo(ProduitVariante::class, 'variante_id');
    }

    public function getSousTotal()
    {
        return $this->prix * $this->quantite;
    }
}
