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

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'manage-unit-kerja-migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'manage-unit-kerja-seeders');

        // Resource publishing tidak diperlukan karena resource dapat dikelola melalui config.
        // Jika pengguna ingin meng-override, mereka dapat menyalin manual dari package atau extend.

        // optional default filesystem resources (views, lang, routes)
        if (is_dir(__DIR__ . '/../resources/views')) {
            $this->loadViewsFrom(__DIR__ . '/../resources/views', 'manage-unit-kerja');
        }

        if (is_dir(__DIR__ . '/../resources/lang')) {
            $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'manage-unit-kerja');
        }

        if (file_exists(__DIR__ . '/../routes/web.php')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }

        // autoload Filament navigation group
        if (class_exists(\Filament\PluginServiceProvider::class)) {
            \Filament\PluginServiceProvider::registerNavigationGroups([
                'unit-kerja',
            ]);
        }
    }

    public function schedule(Schedule $schedule): void
    {
        // optional: schedule package tasks if needed
    }
}
