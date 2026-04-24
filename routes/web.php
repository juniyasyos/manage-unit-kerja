<?php

use Illuminate\Support\Facades\Route;

// Placeholder route file. Package primarily adds Filament resource and migrations.
use Juniyasyos\ManageUnitKerja\Http\Controllers\CenterSyncController;
use Juniyasyos\ManageUnitKerja\Http\Controllers\ClientPushUnitKerjaController;
use Juniyasyos\ManageUnitKerja\Http\Controllers\ClientSyncController;

$middleware = config('manage-unit-kerja.push.middleware', ['api']);
$pushPath = config('manage-unit-kerja.push.path', 'client/push');

Route::prefix('api/manage-unit-kerja')
    ->middleware(['api'])
    ->group(function () use ($middleware, $pushPath) {
        Route::get('center/provision', [CenterSyncController::class, 'provision']);
        Route::post('client/sync', [ClientSyncController::class, 'sync']);
        Route::post($pushPath, [ClientPushUnitKerjaController::class, 'handle'])->middleware($middleware);
    });

Route::middleware(['web'])
    ->group(function () {
        // optional additional web routes can be declared here later
    });
