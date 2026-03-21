<?php

namespace Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource;

class ListUnitKerja extends ListRecords
{
    protected static string $resource = UnitKerjaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Data')
                ->icon('heroicon-m-plus'),
        ];
    }
}
