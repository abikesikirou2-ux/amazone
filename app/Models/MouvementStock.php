<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStock extends Model
{
    protected $table = 'mouvements_stock';

    public $timestamps = false;

    protected $fillable = [
        'produit_id',
        'quantite',
        'type_mouvement',
        'commande_id',
        'notes',
    ];

    protected $dates = ['date_creation'];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public static function enregistrer($produitId, $quantite, $type, $commandeId = null, $notes = null)
    {
        return static::create([
            'produit_id' => $produitId,
            'quantite' => $quantite,
            'type_mouvement' => $type,
            'commande_id' => $commandeId,
            'notes' => $notes,
        ]);
    }
}
