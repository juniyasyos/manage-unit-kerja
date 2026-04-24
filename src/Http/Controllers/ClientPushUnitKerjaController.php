<?php

namespace Juniyasyos\ManageUnitKerja\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Juniyasyos\ManageUnitKerja\Services\UnitKerjaSyncService;

class ClientPushUnitKerjaController extends Controller
{
    public function handle(Request $request): \Illuminate\Http\JsonResponse
    {
        if (! Config::get('manage-unit-kerja.push.active', true)) {
            return response()->json(['message' => 'Push Unit Kerja tidak aktif.'], 403);
        }

        $payload = $request->json()->all();
        $service = new UnitKerjaSyncService();
        $result = $service->sync($payload);

        return response()->json($result);
    }
}
