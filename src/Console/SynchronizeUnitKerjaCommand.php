<?php

namespace Juniyasyos\ManageUnitKerja\Console;

use Illuminate\Console\Command;

class SynchronizeUnitKerjaCommand extends Command
{
    protected $signature = 'manage-unit-kerja:sync';

    protected $description = 'Sinkronisasi Unit Kerja dari App Center (hanya jika sync aktif).';

    public function handle(): int
    {
        if (! config('manage-unit-kerja.sync.active', false)) {
            $this->error('Sinkronisasi tidak aktif. Aktifkan config manage-unit-kerja.sync.active=1 untuk menggunakan fitur ini.');
            return self::FAILURE;
        }

        // TODO: Implementasi provisioning sebenarnya dari App Center.
        // Contoh: panggil service eksternal, request API, simpan hasil, dsb.

        $this->info('Sinkronisasi Unit Kerja dari App Center berhasil dijalankan (placeholder).');

        return self::SUCCESS;
    }
}
