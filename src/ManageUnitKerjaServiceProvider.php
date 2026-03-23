<?php

namespace Juniyasyos\ManageUnitKerja;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class ManageUnitKerjaServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merged config jika perlu
        $configPath = __DIR__ . '/../config/manage-unit-kerja.php';

        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'manage-unit-kerja');
        }

        $this->commands([
            Console\InstallManageUnitKerjaCommand::class,
            Console\PublishManageUnitKerjaCommand::class,
            Console\SynchronizeUnitKerjaCommand::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publishing rules for easy installation
        $this->publishes([
            __DIR__ . '/../config/manage-unit-kerja.php' => config_path('manage-unit-kerja.php'),
        ], 'manage-unit-kerja-config');

        $migrationsPath = __DIR__ . '/../database/migrations';
        if (is_dir($migrationsPath) && count(glob($migrationsPath . '/*.php')) > 0) {
            $this->publishes([
                $migrationsPath => database_path('migrations'),
            ], 'manage-unit-kerja-migrations');
        }

        $seedersPath = __DIR__ . '/../database/seeders';
        if (is_dir($seedersPath) && count(glob($seedersPath . '/*.php')) > 0) {
            $this->publishes([
                $seedersPath => database_path('seeders'),
            ], 'manage-unit-kerja-seeders');
        }

        // Resource publishing tidak diperlukan karena resource dapat dikelola melalui config.
        // Jika pengguna ingin meng-override, mereka dapat menyalin manual dari package atau extend.

        // optional default filesystem resources (views, lang, routes)
        if (is_dir(__DIR__ . '/../resources/views')) {
            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'manage-unit-kerja');
        }

        if (is_dir(__DIR__ . '/../resources/lang')) {
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'manage-unit-kerja');

            // Expose plugin translations to filament-forms namespace because
            // UnitKerjaResource uses `__('filament-forms::unit-kerja.*')`
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'filament-forms');
        }

        if (file_exists(__DIR__ . '/../routes/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }

    public function schedule(Schedule $schedule): void
    {
        // optional: schedule package tasks if needed
    }
}
