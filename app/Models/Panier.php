<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panier extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->hasMany(ArticlePanier::class, 'panier_id');
    }

    public function obtenirTotal()
    {
        return $this->articles->sum(function ($article) {
            return $article->prix * $article->quantite;
        });
    }

    public function nombreArticles()
    {
        return $this->articles->sum('quantite');
    }
}
