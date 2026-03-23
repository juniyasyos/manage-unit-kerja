<?php

namespace Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\Tables;

use Filament\Actions\ActionGroup as ActionsActionGroup;
use Filament\Actions\BulkActionGroup as ActionsBulkActionGroup;
use Filament\Actions\DeleteBulkAction as ActionsDeleteBulkAction;
use Filament\Actions\EditAction as ActionsEditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ForceDeleteAction as ActionsForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction as ActionsForceDeleteBulkAction;
use Filament\Actions\RestoreAction as ActionsRestoreAction;
use Filament\Actions\RestoreBulkAction as ActionsRestoreBulkAction;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource as ManageUnitKerjaResource;
use Juniyasyos\ManageUnitKerja\Models\UnitKerja;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Guava\FilamentModalRelationManagers\Actions\RelationManagerAction;
use Illuminate\Support\Facades\Gate;

class UnitKerjaResourceTable
{
    
    public static function columns(): array
    {
        return [
            TextColumn::make('unit_name')
                ->label(__('filament-forms::unit-kerja.fields.unit_name'))
                ->description(fn(UnitKerja $record) => $record->description)
                ->wrap()
                ->grow()
                ->weight(FontWeight::Bold)
                ->searchable(),
        ];
    }

    public static function filters(): array
    {
        return [
            TrashedFilter::make()
                ->default('with'),
        ];
    }

    public static function headerActions(): array
    {
        return [];
    }

    public static function actions(): array
    {
        return [
            RelationManagerAction::make('users')
                ->slideOver()
                ->label('Pegawai')
                ->icon('heroicon-o-user-group')
                ->color('success')
                ->relationManager(\Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\RelationManagers\UsersRelationManager::make()),

            ActionsActionGroup::make([
                ActionsEditAction::make('edit')
                    ->label('Edit')
                    ->tooltip('Edit')
                    ->visible(fn($record) => ManageUnitKerjaResource::isCrudAllowed() && method_exists($record, 'trashed') && ! $record->trashed())
                    ->icon('heroicon-o-pencil-square'),

                ActionsRestoreAction::make('restore')
                    ->visible(
                        fn($record) => ManageUnitKerjaResource::isCrudAllowed() &&
                            Gate::allows('restore', $record) &&
                            method_exists($record, 'trashed') &&
                            $record->trashed()
                    ),

                ActionsForceDeleteAction::make('forceDelete')
                    ->requiresConfirmation()
                    ->visible(
                        fn($record) => ManageUnitKerjaResource::isCrudAllowed() &&
                            Gate::allows('forceDelete', $record) &&
                            method_exists($record, 'trashed') &&
                            $record->trashed()
                    ),
            ]),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            ActionsBulkActionGroup::make([
                ActionsDeleteBulkAction::make()
                    ->label('Hapus'),

                ActionsRestoreBulkAction::make()
                    ->label('Pulihkan'),

                ActionsForceDeleteBulkAction::make()
                    ->label('Hapus Permanen')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Permanen Data Terpilih')
                    ->modalDescription('Apakah Anda yakin ingin menghapus data ini secara permanen? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus Permanen')
                    ->visible(fn() => Gate::allows('forceDelete', UnitKerja::class)),
            ])->visible(fn() => ManageUnitKerjaResource::isCrudAllowed() && Gate::any(['update_imut::category', 'create_imut::category'])),
        ];
    }
}
