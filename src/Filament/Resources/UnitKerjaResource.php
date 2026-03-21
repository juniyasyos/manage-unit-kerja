<?php

namespace Juniyasyos\ManageUnitKerja\Filament\Resources;

use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\Pages;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\RelationManagers\UsersRelationManager;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\Schema\UnitKerjaResourceSchema;
use Juniyasyos\ManageUnitKerja\Filament\Resources\UnitKerjaResource\Tables\UnitKerjaResourceTable;
use Juniyasyos\ManageUnitKerja\Models\UnitKerja;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class UnitKerjaResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = null;

    protected static ?string $slug = 'unit-kerjas';

    public static function getModel(): string
    {
        return config('manage-unit-kerja.model.unit_kerja', parent::getModel());
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'restore',
            'restore_any',
            'delete',
            'delete_any',
            'force_delete',
            'force_delete_any',
            'attach_user_to_unit_kerja',
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['unit_name'];
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return static::getUrl(name: 'edit', parameters: ['record' => $record]);
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->unit_name;
    }

    public static function getLabel(): ?string
    {
        return __('filament-forms::unit-kerja.navigation.title');
    }

    public static function getPluralLabel(): ?string
    {
        return __('filament-forms::unit-kerja.navigation.plural');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('filament-forms::unit-kerja.navigation.group');
    }

    public static function isCenterApplication(): bool
    {
        return (bool) config('manage-unit-kerja.center_application', false);
    }

    public static function appEnv(): string
    {
        return (string) config('manage-unit-kerja.app_env', app()->environment());
    }

    public static function isLocalEnvironment(): bool
    {
        return in_array(strtolower(static::appEnv()), ['local', 'dev', 'development'], true) || app()->environment('local');
    }

    public static function isCrudAllowed(): bool
    {
        return static::isCenterApplication() || static::isLocalEnvironment();
    }

    public static function isSyncActive(): bool
    {
        return (bool) config('manage-unit-kerja.sync.active', false);
    }

    public static function form(Form $form): Form
    {
        return $form->schema(UnitKerjaResourceSchema::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(UnitKerjaResourceTable::columns())
            ->filters(UnitKerjaResourceTable::filters())
            ->headerActions(UnitKerjaResourceTable::headerActions())
            ->actions(UnitKerjaResourceTable::actions())
            ->bulkActions(UnitKerjaResourceTable::bulkActions());
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUnitKerja::route('/'),
            'create' => Pages\CreateUnitKerja::route('/create'),
            'edit' => Pages\EditUnitKerja::route('/{record:slug}/edit'),
        ];
    }
}
