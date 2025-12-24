<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class PointRelais extends Model
{
    protected $table = 'points_relais';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'code_postal',
        'telephone',
        'horaires_ouverture',
        'latitude',
        'longitude',
        'actif',
    ];

    protected $casts = [
        'horaires_ouverture' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'actif' => 'boolean',
    ];

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public static function rechercherParCodePostal($codePostal)
    {
        return static::where('actif', true)
            ->where('code_postal', $codePostal)
            ->get();
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    protected static function booted(): void
    {
        static::saving(function (PointRelais $pr) {
            if ((empty($pr->latitude) || empty($pr->longitude)) && $pr->adresse && $pr->ville) {
                try {
                    $coords = self::geocoderAdresse($pr->adresse, $pr->code_postal, $pr->ville, $pr->nom);
                    if ($coords) {
                        [$lat, $lng] = $coords;
                        $pr->latitude = $lat;
                        $pr->longitude = $lng;
                    }
                } catch (\Throwable $e) {
                    // En cas d'échec de géocodage, on laisse vide
                }
            }
        });
    }

    /**
     * Géocoder l'adresse via Nominatim (OpenStreetMap).
     * Retourne [lat, lng] ou null.
     */
    public static function geocoderAdresse(?string $adresse, ?string $codePostal, ?string $ville, ?string $nom = null): ?array
    {
        $query = trim(($nom ? ($nom . ', ') : '') . ($adresse ?? '') . ' ' . ($codePostal ?? '') . ' ' . ($ville ?? ''));
        if ($query === '') {
            return null;
        }
        try {
            $resp = Http::withHeaders([
                'User-Agent' => 'MiniAmazon/1.0 (+https://example.com)'
            ])->get('https://nominatim.openstreetmap.org/search', [
                'format' => 'jsonv2',
                'q' => $query,
                'limit' => 1,
            ]);
            if ($resp->successful()) {
                $json = $resp->json();
                if (is_array($json) && isset($json[0]['lat'], $json[0]['lon'])) {
                    return [ (float) $json[0]['lat'], (float) $json[0]['lon'] ];
                }
            }
        } catch (\Throwable $e) {
            // Ignore
        }
        return null;
    }
}
