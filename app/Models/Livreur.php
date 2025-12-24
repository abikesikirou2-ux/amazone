<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Livreur extends Authenticatable
{
    use HasFactory;

    protected $table = 'livreurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'ville',
        'quartier',
        'password',
        'disponible',
        'valide',
        'valide_le',
        'validation_token',
        'refuse_le',
        'validation_envoye_le',
    ];

    protected $casts = [
        'disponible' => 'boolean',
        'valide' => 'boolean',
        'valide_le' => 'datetime',
        'refuse_le' => 'datetime',
        'validation_envoye_le' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public static function trouverDisponible($ville, $quartier = null)
    {
        $query = static::where('disponible', true)->where('ville', $ville);
        
        if ($quartier) {
            $query->where('quartier', $quartier);
        }
        
        return $query->first();
    }

    public function confirmerDisponibilite($disponible = true)
    {
        $this->update(['disponible' => $disponible]);
    }
}
