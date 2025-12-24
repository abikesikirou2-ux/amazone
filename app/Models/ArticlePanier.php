<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticlePanier extends Model
{
    use HasFactory;

    protected $table = 'articles_panier';

    protected $fillable = [
        'panier_id',
        'produit_id',
        'variante_id',
        'taille',
        'quantite',
        'prix',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    public function panier()
    {
        return $this->belongsTo(Panier::class);
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
