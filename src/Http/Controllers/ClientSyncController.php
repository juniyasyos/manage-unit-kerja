<?php

namespace Juniyasyos\ManageUnitKerja\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Juniyasyos\ManageUnitKerja\Models\UnitKerja;

class ClientSyncController extends Controller
{
    public function sync(Request $request): \Illuminate\Http\JsonResponse
    {
        if (! Config::get('manage-unit-kerja.sync.active', false)) {
            return response()->json(['message' => 'Sinkronisasi tidak aktif.'], 403);
        }

        $centerUrl = Config::get('manage-unit-kerja.app_center_url');

        if (empty($centerUrl)) {
            return response()->json(['message' => 'URL App Center belum diset.'], 422);
        }

        $provisionEndpoint = rtrim($centerUrl, '/') . '/api/manage-unit-kerja/center/provision';

        $response = Http::timeout(15)->get($provisionEndpoint);

        if (! $response->successful()) {
            return response()->json([
                'message' => 'Gagal mendapatkan data dari App Center.',
                'status' => 'error',
                'http_status' => $response->status(),
            ], 500);
        }

        $payload = $response->json();

        if (! isset($payload['data']) || ! is_array($payload['data'])) {
            return response()->json(['message' => 'Format data dari App Center tidak valid.'], 500);
        }

        $unitModelClass = Config::get('manage-unit-kerja.model.unit_kerja', UnitKerja::class);
        $userModelClass = Config::get('manage-unit-kerja.model.user', \App\Models\User::class);

        $units = $payload['data']['units'] ?? $payload['data'];
        $users = $payload['data']['users'] ?? [];
        $userUnitRelations = $payload['data']['user_unit_kerja'] ?? $payload['data']['userUnitKerja'] ?? [];

        $syncedCount = 0;

        // Sync Units
        foreach ($units as $item) {
            if (! isset($item['slug']) || empty($item['slug'])) {
                continue;
            }

            $unit = $unitModelClass::updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'unit_name' => $item['unit_name'] ?? null,
                    'description' => $item['description'] ?? null,
                ]
            );

            $syncedCount++;
        }

        // Sync Users
        $userIndexByNip = [];

        foreach ($users as $item) {
            if (empty($item['nip'])) {
                continue;
            }

            $query = $userModelClass::query();
            $query->where('nip', $item['nip']);

            $existingUser = $query->first();

            $data = array_filter([
                'name' => $item['name'] ?? null,
                'nip' => $item['nip'] ?? null,
                'status' => $item['status'] ?? null,
                'iam_id' => $item['iam_id'] ?? null,
                'active' => $item['active'] ?? null,
            ], fn($value) => ! is_null($value));

            if ($existingUser) {
                $existingUser->update($data);
                $user = $existingUser;
            } else {
                $data['password'] = $item['password'] ?? 'Rschjaya1234';
                $user = $userModelClass::create($data);
            }

            if (! empty($item['nip'])) {
                $userIndexByNip[$item['nip']] = $user->id;
            }
            if (! empty($item['email'])) {
                $userIndexByEmail[$item['email']] = $user->id;
            }
        }

        // Sync relation
        foreach ($userUnitRelations as $relation) {
            $unit = null;
            $user = null;

            if (! empty($relation['unit_slug'])) {
                $unit = $unitModelClass::where('slug', $relation['unit_slug'])->first();
            }

            if (! $unit && ! empty($relation['unit_kerja_id'])) {
                $unit = $unitModelClass::find($relation['unit_kerja_id']);
            }

            if (! empty($relation['user_nip']) && ! empty($userIndexByNip[$relation['user_nip']])) {
                $user = $userModelClass::find($userIndexByNip[$relation['user_nip']]);
            }

            if (! $user && ! empty($relation['user_id'])) {
                $user = $userModelClass::find($relation['user_id']);
            }

            if ($unit && $user) {
                if (method_exists($unit, 'users')) {
                    $unit->users()->syncWithoutDetaching([$user->id]);
                }
            }
        }

        return response()->json([
            'message' => 'Sinkronisasi berhasil.',
            'synced' => $syncedCount,
            'units' => count($units),
            'users' => count($users),
            'relations' => count($userUnitRelations),
        ]);
    }
}
