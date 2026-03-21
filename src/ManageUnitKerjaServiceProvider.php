<?php

namespace Juniyasyos\ManageUnitKerja;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'manage-unit-kerja-migrations');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'manage-unit-kerja-seeders');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'manage-unit-kerja');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'manage-unit-kerja');

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // autoload Filament resource path
        if (class_exists(\Filament\PluginServiceProvider::class)) {
            \Filament\PluginServiceProvider::registerNavigationGroups([
                'unit-kerja',
            ]);
        }
    }
}
