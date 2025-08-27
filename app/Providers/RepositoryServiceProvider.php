<?php

namespace App\Providers;

use App\Interfaces\VehiclePartRepositoryInterface;
use App\Interfaces\VehicleRepositoryInterface;
use App\Interfaces\DefectReportRepositoryInterface;
use App\Interfaces\LocationRepositoryInterface;
use App\Interfaces\ReportsRepositoryInterface;
use App\Repositories\VehiclePartRepository;
use App\Repositories\VehicleRepository;
use App\Repositories\DefectReportRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ReportsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(VehiclePartRepositoryInterface::class, VehiclePartRepository::class);
        $this->app->bind(VehicleRepositoryInterface::class, VehicleRepository::class);
        $this->app->bind(DefectReportRepositoryInterface::class, DefectReportRepository::class);
        $this->app->bind(LocationRepositoryInterface::class, LocationRepository::class);
        $this->app->bind(ReportsRepositoryInterface::class, ReportsRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
