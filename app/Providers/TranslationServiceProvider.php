<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\TranslationHelper;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register @trans directive
        Blade::directive('trans', function ($expression) {
            return "<?php echo App\Helpers\TranslationHelper::trans($expression); ?>";
        });
        
        // Register @transChoice directive for pluralization
        Blade::directive('transChoice', function ($expression) {
            return "<?php echo App\Helpers\TranslationHelper::transChoice($expression); ?>";
        });
        
        // Share translation helper with all views
        view()->composer('*', function ($view) {
            $view->with('trans', function ($key) {
                return TranslationHelper::trans($key);
            });
        });
    }
}
