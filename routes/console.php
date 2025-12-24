<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Produit;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('products:images {--force}', function () {
    $this->info('Génération des images produits...');

    // S'assurer que le dossier existe
    Storage::disk('public')->makeDirectory('produits');

    $makePlaceholder = function (string $path, string $text) {
        // Fallback local: génère une image PNG via GD
        $width = 800; $height = 600;
        $img = \imagecreatetruecolor($width, $height);
        $bg = \imagecolorallocate($img, 102, 126, 234); // bleu
        $fg = \imagecolorallocate($img, 255, 255, 255); // blanc
        \imagefilledrectangle($img, 0, 0, $width, $height, $bg);
        // Écrire le texte au centre approximatif avec police builtin
        $lines = wordwrap($text, 24, "\n");
        $y = (int)($height / 2) - 20;
        foreach (explode("\n", $lines) as $i => $line) {
            $w = 8 * strlen($line); // approx width per char for built-in font 5
            $x = (int)(($width - $w) / 2);
            \imagestring($img, 5, max(10, $x), $y + $i * 24, $line, $fg);
        }
        \ob_start();
        \imagepng($img);
        $data = \ob_get_clean();
        \imagedestroy($img);
        Storage::disk('public')->put($path, $data);
    };

    $count = 0;
    foreach (Produit::all() as $produit) {
        $filename = $produit->image ?: (Str::slug($produit->nom) . '-' . $produit->id . '.jpg');
        $path = 'produits/' . $filename;

        if (! $this->option('force') && Storage::disk('public')->exists($path)) {
            $this->line("- Skip: {$produit->nom} (existe)");
            continue;
        }

        $seed = Str::slug($produit->nom) . '-' . $produit->id;
        // Essai 1: picsum seed (rapidement unique)
        $url = "https://picsum.photos/seed/{$seed}/800/600";

        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                Storage::disk('public')->put($path, $response->body());
                if (! $produit->image) {
                    $produit->image = $filename;
                    $produit->save();
                }
                $this->line("+ OK: {$produit->nom} → {$path}");
                $count++;
            } else {
                $this->warn("! Échec téléchargement pour {$produit->nom} ({$response->status()})");
                // Fallback local
                $makePlaceholder($path, $produit->nom);
                if (! $produit->image) {
                    $produit->image = $filename;
                    $produit->save();
                }
                $this->line("+ Fallback local: {$produit->nom} → {$path}");
                $count++;
            }
        } catch (\Throwable $e) {
            $this->warn("! Erreur pour {$produit->nom}: " . $e->getMessage());
            // Fallback local
            $makePlaceholder($path, $produit->nom);
            if (! $produit->image) {
                $produit->image = $filename;
                $produit->save();
            }
            $this->line("+ Fallback local: {$produit->nom} → {$path}");
            $count++;
        }
    }

    $this->info("Terminé. {$count} images générées.");
})->purpose('Télécharger et enregistrer des images pour les produits');
