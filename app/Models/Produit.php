<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'image',
        'categorie_id',
        'segment',
        'stock',
        'actif',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function avis()
    {
        return $this->hasMany(Avis::class, 'produit_id');
    }

    public function images()
    {
        return $this->hasMany(ProduitImage::class)->orderBy('ordre');
    }

    public function variantes()
    {
        return $this->hasMany(ProduitVariante::class);
    }

    public function mouvementsStock()
    {
        return $this->hasMany(MouvementStock::class, 'produit_id');
    }

    public function moyenneNotes()
    {
        return $this->avis()->avg('note') ?? 0;
    }

    public function nombreAvis()
    {
        return $this->avis()->count();
    }

    public function verifierStock($quantite)
    {
        return $this->stock >= $quantite && $this->actif;
    }

    public function diminuerStock($quantite)
    {
        $this->decrement('stock', $quantite);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true)->where('stock', '>', 0);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            $img = ltrim($this->image, '/');
            // Si l'image est une URL absolue
            if (str_starts_with($img, 'http')) {
                return $img;
            }
            // Vérifier telle quelle dans le disque public
            if (Storage::disk('public')->exists($img)) {
                return asset('storage/' . $img);
            }
            // Sinon, tenter sous-dossier produits/
            $storagePath = 'produits/' . $img;
            if (Storage::disk('public')->exists($storagePath)) {
                return asset('storage/' . $storagePath);
            }
            // Fallback vers images publiques du thème
            $publicPath = public_path('images/produits/' . $img);
            if (is_file($publicPath)) {
                return asset('images/produits/' . $img);
            }
        }

        $text = urlencode($this->nom ?? 'Produit');
        return "https://placehold.co/800x600?text={$text}";
    }

    public function getPrixEuroAttribute()
    {
        // Affichage en FCFA sans décimales
        return number_format((float)$this->prix, 0, ',', ' ') . ' FCFA';
    }

    public function getPrixFcfaAttribute()
    {
        return number_format((float)$this->prix, 0, ',', ' ') . ' FCFA';
    }
}
