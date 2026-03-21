<?php

namespace Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\RelationManagers\UsersRelationManager;
use Guava\FilamentModalRelationManagers\Actions\Action\RelationManagerAction;

class EditUnitKerja extends EditRecord
{
    protected static string $resource = UnitKerjaResource::class;

    // customize redirect after create
    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            RelationManagerAction::make('users')
                ->slideOver()
                ->icon('heroicon-o-user')
                ->record($this->getRecord())
                ->label(__('filament-forms::unit-kerja.actions.attach'))
                ->relationManager(UsersRelationManager::make()),
        ];
    }
}
