<?php

namespace Juniyasyos\ManageUnitKerja\Console;

use Illuminate\Console\Command;
use Juniyasyos\ManageUnitKerja\Http\Controllers\ClientSyncController;

class SynchronizeUnitKerjaCommand extends Command
{
    protected $signature = 'manage-unit-kerja:sync';

    protected $description = 'Sinkronisasi Unit Kerja dan User dari App Center (hanya jika sync aktif).';

    public function handle(): int
    {
        if (! config('manage-unit-kerja.sync.active', false)) {
            $this->error('Sinkronisasi tidak aktif. Aktifkan config manage-unit-kerja.sync.active=1 untuk menggunakan fitur ini.');
            return self::FAILURE;
        }

        $controller = app(ClientSyncController::class);
        $response = $controller->sync(request());

        if ($response->getStatusCode() !== 200) {
            $this->error('Sinkronisasi gagal. ' . ($response->getData()->message ?? ''));
            return self::FAILURE;
        }

        $data = $response->getData();

        $this->info('Sinkronisasi berhasil. Unit: ' . ($data->synced ?? 0) . ', Pengguna: ' . ($data->users ?? 0) . ', Relasi: ' . ($data->relations ?? 0));

        return self::SUCCESS;
    }
}
