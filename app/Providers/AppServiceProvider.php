<?php

namespace App\Providers;

use App\Interfaces\VehiclePartRepositoryInterface;
use App\Repositories\VehiclePartRepository;
use App\View\Components\RequiredAsterisk;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(VehiclePartRepositoryInterface::class, VehiclePartRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::component('req', RequiredAsterisk::class);
    }
}
