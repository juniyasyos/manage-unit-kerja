<?php

namespace Juniyasyos\ManageUnitKerja;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ManageUnitKerjaPlugin implements Plugin
{
    private bool $isActive = false;

    public function getId(): string
    {
        return 'manage-unit-kerja';
    }

    public function register(Panel $panel): void
    {
        $this->isActive = config('manage-unit-kerja.active', true);

        if (! $this->isActive) {
            return;
        }

        $resources = config('manage-unit-kerja.filament.resources', []);

        if (! empty($resources)) {
            $panel->resources($resources);
        }
    }

    public function boot(Panel $panel): void
    {
        // no-op
    }

    public static function make(): static
    {
        return new static;
    }
}
