<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use App\Helpers\NumberHelper;

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
        Paginator::useTailwind();

        // Register NumberHelper as global helper
        Blade::directive('formatCurrency', function ($expression) {
            return "<?php echo App\Helpers\NumberHelper::formatCurrency($expression); ?>";
        });

        Blade::directive('formatNumber', function ($expression) {
            return "<?php echo App\Helpers\NumberHelper::formatNumber($expression); ?>";
        });

        Blade::directive('formatCurrencyFull', function ($expression) {
            return "<?php echo App\Helpers\NumberHelper::formatCurrencyFull($expression); ?>";
        });
    }
}

