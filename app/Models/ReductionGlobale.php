<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ReductionGlobale extends Model
{
    use HasFactory;

    protected $table = 'reductions_globales';

    protected $fillable = [
        'date_debut',
        'date_fin',
        'pourcentage',
        'actif',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'pourcentage' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function scopeActive($query)
    {
        // Actif si la date du jour est comprise entre début et fin (inclus)
        $today = Carbon::today();
        return $query->where('actif', true)
            ->whereDate('date_debut', '<=', $today)
            ->whereDate('date_fin', '>=', $today);
    }

    public function calculerReduction(float $montant): float
    {
        $pct = (float) $this->pourcentage;
        return round($montant * ($pct / 100), 2);
    }

    public static function autoExpire(): void
    {
        try {
            static::query()
                ->where('actif', true)
                ->whereDate('date_fin', '<', Carbon::today())
                ->update(['actif' => false]);
        } catch (\Throwable $e) {
            // Ignorer en cas d'absence de table pendant migrations
        }
    }
}
