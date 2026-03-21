<?php

use Illuminate\Support\Facades\Route;

// Placeholder route file. Package primarily adds Filament resource and migrations.
use Juniyasyos\ManageUnitKerja\Http\Controllers\CenterSyncController;
use Juniyasyos\ManageUnitKerja\Http\Controllers\ClientSyncController;

Route::prefix('api/manage-unit-kerja')
    ->middleware(['api'])
    ->group(function () {
        Route::get('center/provision', [CenterSyncController::class, 'provision']);
        Route::post('client/sync', [ClientSyncController::class, 'sync']);
    });

Route::middleware(['web'])
    ->group(function () {
        // optional additional web routes can be declared here later
    });
