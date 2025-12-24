<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdresseLivraison extends Model
{
    protected $table = 'adresses_livraison';

    protected $fillable = [
        'user_id',
        'nom_complet',
        'telephone',
        'adresse',
        'ville',
        'quartier',
        'code_postal',
        'pays',
        'par_defaut',
    ];

    protected $casts = [
        'par_defaut' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function definirParDefaut()
    {
        // Retirer le statut par défaut des autres adresses
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['par_defaut' => false]);

        $this->update(['par_defaut' => true]);
    }
}
