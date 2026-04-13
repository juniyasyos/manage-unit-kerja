<?php

namespace Juniyasyos\ManageUnitKerja\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Juniyasyos\ManageUnitKerja\Models\UnitKerja;

class CenterSyncController extends Controller
{
    public function provision(): \Illuminate\Http\JsonResponse
    {
        if (! Config::get('manage-unit-kerja.center_application', false)) {
            return response()->json(['message' => 'App center tidak diaktifkan pada konfigurasi ini.'], 403);
        }

        $unitModel = Config::get('manage-unit-kerja.model.unit_kerja', UnitKerja::class);
        $userModel = Config::get('manage-unit-kerja.model.user', \App\Models\User::class);

        $unitInstance = new $unitModel();
        $userInstance = new $userModel();

        $units = $unitModel::query()
            ->whereNull('deleted_at')
            ->get(['id', 'unit_name', 'description', 'slug', 'created_at', 'updated_at']);

        // Build user query with dynamic column selection and iam_id mapping
        // iam_id is the user id from the center app, status may or may not exist
        $userQuery = $userModel::query()
            ->when(method_exists($userModel, 'trashed') || method_exists($userInstance, 'getDeletedAtColumn'), 
                fn($query) => $query->whereNull('deleted_at'));
        
        // Check which columns actually exist on this table
        $userTable = $userInstance->getTable();
        $existingColumns = \Illuminate\Support\Facades\Schema::getColumnListing($userTable);
        
        // Build select array dynamically
        $selectColumns = [];
        foreach (['id', 'nip', 'name', 'email', 'status', 'active', 'created_at', 'updated_at'] as $col) {
            if (in_array($col, $existingColumns)) {
                $selectColumns[] = $col;
            }
        }
        
        // Ensure id is always selected (for iam_id mapping)
        if (!in_array('id', $selectColumns)) {
            $selectColumns[] = 'id';
        }
        
        $users = $userQuery->get($selectColumns)->map(function ($user) {
            // Map id to iam_id for client app compatibility
            if (isset($user->id)) {
                $user->iam_id = $user->id;
            }
            return $user;
        });

        $unitTable = $unitInstance->getTable();

        $relations = DB::table('user_unit_kerja')
            ->join($userTable, 'user_unit_kerja.user_id', '=', "{$userTable}.id")
            ->join($unitTable, 'user_unit_kerja.unit_kerja_id', '=', "{$unitTable}.id")
            ->select(
                'user_unit_kerja.user_id',
                'user_unit_kerja.unit_kerja_id',
                'user_unit_kerja.created_at as attached_at',
                'user_unit_kerja.updated_at as attached_updated_at',
                "{$userTable}.nip as user_nip",
                "{$userTable}.email as user_email",
                "{$unitTable}.slug as unit_slug"
            )
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'units' => $units,
                'users' => $users,
                'user_unit_kerja' => $relations,
            ],
        ]);
    }
}
