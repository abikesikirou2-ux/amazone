<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
    ];

    public function produits()
    {
        return $this->hasMany(Produit::class, 'categorie_id');
    }

    // Icône Font Awesome correspondant au slug de la catégorie
    public function getIconeAttribute(): string
    {
        $slug = method_exists($this, 'getAttribute') ? (string) ($this->attributes['slug'] ?? \Str::slug((string) $this->attributes['nom'] ?? '')) : '';
        $slug = strtolower($slug);

        $map = [
            'electronique' => 'fas fa-tv',
            'electromenager' => 'fas fa-blender',
            'informatique' => 'fas fa-laptop',
            'telephones' => 'fas fa-mobile-alt',
            'mode' => 'fas fa-tshirt',
            'vetements' => 'fas fa-tshirt',
            'chaussures' => 'fas fa-shoe-prints',
            'beaute' => 'fas fa-spa',
            'sante' => 'fas fa-heartbeat',
            'maison' => 'fas fa-home',
            'bricolage' => 'fas fa-tools',
            'jardin' => 'fas fa-seedling',
            'sport' => 'fas fa-dumbbell',
            'auto' => 'fas fa-car',
            'moto' => 'fas fa-motorcycle',
            'jeux-video' => 'fas fa-gamepad',
            'jouets' => 'fas fa-puzzle-piece',
            'livres' => 'fas fa-book',
            'alimentation' => 'fas fa-apple-alt',
            'bijoux' => 'fas fa-gem',
            'enfants' => 'fas fa-baby',
            'animaux' => 'fas fa-paw',
            'bureau' => 'fas fa-briefcase',
            'musique' => 'fas fa-music',
            'arts' => 'fas fa-palette',
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        // Fallback: heuristique sur le nom
        $nom = strtolower((string) ($this->attributes['nom'] ?? ''));
        foreach ($map as $key => $ico) {
            if ($nom !== '' && str_contains($nom, $key)) {
                return $ico;
            }
        }

        return 'fas fa-tag';
    }

    // Couleur Tailwind dédiée par catégorie
    public function getCouleurAttribute(): string
    {
        $slug = method_exists($this, 'getAttribute') ? (string) ($this->attributes['slug'] ?? \Str::slug((string) $this->attributes['nom'] ?? '')) : '';
        $slug = strtolower($slug);

        $map = [
            'electronique' => 'text-indigo-600',
            'electromenager' => 'text-amber-600',
            'informatique' => 'text-cyan-600',
            'telephones' => 'text-blue-600',
            'mode' => 'text-rose-600',
            'vetements' => 'text-pink-600',
            'chaussures' => 'text-orange-600',
            'beaute' => 'text-fuchsia-600',
            'sante' => 'text-red-600',
            'maison' => 'text-emerald-600',
            'bricolage' => 'text-slate-600',
            'jardin' => 'text-lime-600',
            'sport' => 'text-green-600',
            'auto' => 'text-gray-700',
            'moto' => 'text-zinc-600',
            'jeux-video' => 'text-purple-600',
            'jouets' => 'text-teal-600',
            'livres' => 'text-yellow-600',
            'alimentation' => 'text-emerald-700',
            'bijoux' => 'text-violet-600',
            'enfants' => 'text-sky-600',
            'animaux' => 'text-brown-600',
            'bureau' => 'text-stone-600',
            'musique' => 'text-red-700',
            'arts' => 'text-indigo-700',
        ];

        if (isset($map[$slug])) {
            return $map[$slug];
        }

        // Palette fallback pour varier les couleurs (éviter toutes identiques)
        $palette = [
            'text-blue-600', 'text-emerald-600', 'text-amber-600', 'text-indigo-600', 'text-cyan-600',
            'text-purple-600', 'text-lime-600', 'text-teal-600', 'text-red-600', 'text-orange-600',
            'text-fuchsia-600', 'text-sky-600', 'text-violet-600', 'text-green-600', 'text-rose-600',
        ];
        $index = (int) ($this->attributes['id'] ?? 0);
        if ($index <= 0) {
            return 'text-blue-600';
        }
        return $palette[$index % count($palette)];
    }
}
