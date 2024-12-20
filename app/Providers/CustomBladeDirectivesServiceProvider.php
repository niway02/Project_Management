<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CustomBladeDirectivesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('authBoth', function () {
            return "<?php if (auth('web')->check() || auth('client')->check()): ?>";
        });

        Blade::directive('endauthBoth', function () {
            return '<?php endif; ?>';
        });

        Blade::directive('hasRole', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasRole($expression)): ?>";
        });
    
        Blade::directive('elsehasRole', function ($expression) {
            return "<?php elseif(auth()->check() && auth()->user()->hasRole($expression)): ?>";
        });
    
        Blade::directive('endhasRole', function () {
            return '<?php endif; ?>';
        });
    }
}
