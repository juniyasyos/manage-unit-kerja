<?php

namespace Juniyasyos\ManageUnitKerja\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Juniyasyos\ManageUnitKerja\Models\UnitKerja;

class CenterSyncController extends Controller
{
    public function provision(): \Illuminate\Http\JsonResponse
    {
        if (! Config::get('manage-unit-kerja.center_application', false)) {
            return response()->json(['message' => 'App center tidak diaktifkan pada konfigurasi ini.'], 403);
        }

        $units = UnitKerja::query()->whereNull('deleted_at')->get(['id', 'unit_name', 'description', 'slug', 'created_at', 'updated_at']);

        return response()->json([
            'status' => 'success',
            'data' => $units,
        ]);
    }
}
