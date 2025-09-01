<?php

namespace App\Providers;

use App\Helpers\DateHelper;
use App\Models\DefectReport;
use App\Observers\DefectReportObserver;
use App\View\Components\RequiredAsterisk;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;


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
        // Register the required asterisk component
        Blade::component('req', RequiredAsterisk::class);

        // Blade directive for formatting dates
        Blade::directive('formatDate', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatDate($expression); ?>";
        });

        // Blade directive for formatting created_at
        Blade::directive('formatCreatedAt', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatCreatedAt($expression); ?>";
        });

        // Blade directive for formatting updated_at
        Blade::directive('formatUpdatedAt', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::formatUpdatedAt($expression); ?>";
        });

        // Blade directive for relative time
        Blade::directive('relativeTime', function ($expression) {
            return "<?php echo \App\Helpers\DateHelper::getRelativeTime($expression); ?>";
        });

        DefectReport::observe(DefectReportObserver::class);

    }
}
