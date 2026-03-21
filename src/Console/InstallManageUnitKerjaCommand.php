<?php

namespace Juniyasyos\ManageUnitKerja\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallManageUnitKerjaCommand extends Command
{
    protected $signature = 'manage-unit-kerja:install {--no-migrate : Skip database migration} {--no-seed : Skip database seeding}';
    protected $description = 'Install Manage Unit Kerja plugin with config/migration/seeder/resources.';

    public function handle(): int
    {
        $this->info('Installing Manage Unit Kerja package...');

        // Pastikan model/schema/resource sudah ada di package
        $unitKerjaModel = base_path('packages/juniyasyos/manage-unit-kerja/src/Models/UnitKerja.php');
        $schemaFile = base_path('packages/juniyasyos/manage-unit-kerja/src/Filament/Resources/UnitKerjaResource/Schema/UnitKerjaResourceSchema.php');
        $resourceFile = base_path('packages/juniyasyos/manage-unit-kerja/src/Filament/Resources/UnitKerjaResource.php');

        if (! file_exists($unitKerjaModel) || ! file_exists($schemaFile) || ! file_exists($resourceFile)) {
            $this->warn('Model/schema/resource belum lengkap. Harap periksa:');
            if (! file_exists($unitKerjaModel)) {
                $this->warn('- Model UnitKerja tidak ditemukan: ' . $unitKerjaModel);
            }
            if (! file_exists($schemaFile)) {
                $this->warn('- Schema UnitKerjaResourceSchema tidak ditemukan: ' . $schemaFile);
            }
            if (! file_exists($resourceFile)) {
                $this->warn('- Resource UnitKerjaResource tidak ditemukan: ' . $resourceFile);
            }
            $this->warn('Install tetap berjalan tetapi sync config/extend manual dibutuhkan.');
        } else {
            $this->info('Model/schema/resource terdeteksi, lanjut install.');
        }

        $this->call('manage-unit-kerja:publish', ['--tag' => 'manage-unit-kerja-config']);
        $this->call('manage-unit-kerja:publish', ['--tag' => 'manage-unit-kerja-migrations']);
        $this->call('manage-unit-kerja:publish', ['--tag' => 'manage-unit-kerja-seeders']);

        // resource tidak dipublish otomatis; gunakan config untuk pengaturan model/resource

        if (! $this->option('no-migrate')) {
            $this->info('Running migration...');
            Artisan::call('migrate', ['--force' => true]);
            $this->line(Artisan::output());
        }

        if (! $this->option('no-seed')) {
            $this->info('Running seed: UnitKerjaSeeder...');
            Artisan::call('db:seed', [
                '--class' => 'Juniyasyos\\ManageUnitKerja\\Database\\Seeders\\UnitKerjaSeeder',
                '--force' => true,
            ]);
            $this->line(Artisan::output());
        }

        $this->info('Manage Unit Kerja installation completed.');

        return self::SUCCESS;
    }
}
