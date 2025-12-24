<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use App\Models\Categorie;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partage global des catégories pour le menu principal
        try {
            View::share('categoriesMenu', Categorie::query()->orderBy('nom')->get());
        } catch (\Throwable $e) {
            // En phase d'installation/migration, ignorer les erreurs de table manquante
        }

        // Directive Blade pour format FCFA uniforme
        Blade::directive('fcfa', function ($expression) {
            return "<?php echo number_format((float)($expression), 0, ',', ' ') . ' FCFA'; ?>";
        });
    }
}
