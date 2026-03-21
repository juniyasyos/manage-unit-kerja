<?php

namespace Juniyasyos\ManageUnitKerja\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishManageUnitKerjaCommand extends Command
{
    protected $signature = 'manage-unit-kerja:publish {--tag=manage-unit-kerja-config}';
    protected $description = 'Publish Manage Unit Kerja package configuration, migrations, seeder, and resources.';

    public function handle(): int
    {
        $tag = $this->option('tag');

        $this->info('Publishing ' . $tag . '...');

        Artisan::call('vendor:publish', [
            '--tag' => $tag,
            '--force' => true,
        ]);

        $this->line(Artisan::output());

        $this->info('Done.');

        return Command::SUCCESS;
    }
}
