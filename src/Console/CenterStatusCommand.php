<?php

namespace Juniyasyos\ManageUnitKerja\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CenterStatusCommand extends Command
{
    protected $signature = 'manage-unit-kerja:center-status';
    protected $description = 'Check Manage Unit Kerja Center app status and provisioning payload.';

    public function handle(): int
    {
        $centerUrl = Config::get('manage-unit-kerja.app_center_url');

        if (empty($centerUrl)) {
            $this->error('MANAGE_UNIT_KERJA_APP_CENTER_URL belum diset di config/.env');
            return self::FAILURE;
        }

        $endpoint = rtrim($centerUrl, '/') . '/api/manage-unit-kerja/center/provision';

        try {
            $response = Http::timeout(15)->get($endpoint);
        } catch (\Exception $e) {
            $this->error('Gagal terhubung ke Center: ' . $e->getMessage());
            return self::FAILURE;
        }

        if (! $response->successful()) {
            $this->error('Center provisioning endpoint menolak request. HTTP ' . $response->status());
            $this->line($response->body());
            return self::FAILURE;
        }

        $payload = $response->json();

        if (! isset($payload['data']) || ! is_array($payload['data'])) {
            $this->error('Response center tidak memuat data yang diharapkan.');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            return self::FAILURE;
        }

        $units = $payload['data']['units'] ?? [];
        $users = $payload['data']['users'] ?? [];
        $relations = $payload['data']['user_unit_kerja'] ?? [];

        $this->info('Center provisioning tersedia.');
        $this->line('URL: ' . $endpoint);
        $this->line('Unit: ' . count($units));
        $this->line('Users: ' . count($users));
        $this->line('Relations: ' . count($relations));

        if (count($units) === 0 && count($users) === 0 && count($relations) === 0) {
            $this->warn('Payload center kosong, silakan periksa sumber data dan seeder center.');
        }

        return self::SUCCESS;
    }
}
