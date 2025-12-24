<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModeLivraison extends Model
{
    protected $table = 'modes_livraison';

    protected $fillable = [
        'nom',
        'prix',
        'jours_estimes',
        'actif',
        'description',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
