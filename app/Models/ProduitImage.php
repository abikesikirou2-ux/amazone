<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProduitImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'produit_id',
        'path',
        'ordre',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function getUrlAttribute()
    {
        $storagePath = ltrim($this->path, '/');
        if (Storage::disk('public')->exists($storagePath)) {
            return asset('storage/' . $storagePath);
        }
        if (str_starts_with($this->path, 'http')) {
            return $this->path;
        }
        return asset($this->path);
    }
}
