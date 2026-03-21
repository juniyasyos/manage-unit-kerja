# Manage Unit Kerja Package

Package Laravel/Filament untuk manajemen unit kerja, dengan dukungan mode center/client dan sinkronisasi.

## Fitur utama
- 
Filament Resource untuk model `UnitKerja`.
- Contoh konfig `app_env`, `center_application`, `sync.active`, dan `app_center_url`.
- Logika CRUD: hanya boleh bila `center_application` true atau environment local.
- `sync.active` menyalakan fitur sinkronisasi.
- Mode center: endpoint provisioning `GET /api/manage-unit-kerja/center/provision`.
- Mode client: endpoint sync `POST /api/manage-unit-kerja/client/sync`.

## Install
1. Pasang package melalui composer (jika belum):

```bash
composer require juniyasyos/manage-unit-kerja
```

2. Publish config, migrasi, seeder:

```bash
php artisan vendor:publish --tag=manage-unit-kerja-config
php artisan vendor:publish --tag=manage-unit-kerja-migrations
php artisan vendor:publish --tag=manage-unit-kerja-seeders
```

3. Migrate dan seed (opsional):

```bash
php artisan migrate
php artisan db:seed --class="Juniyasyos\\ManageUnitKerja\\Database\\Seeders\\UnitKerjaSeeder"
```

## Konfigurasi
Edit `config/manage-unit-kerja.php` atau via `.env`:

- `MANAGE_UNIT_KERJA_APP_ENV` (default `APP_ENV` / `production`)
- `MANAGE_UNIT_KERJA_CENTER_APPLICATION` (`true` / `false`)
- `MANAGE_UNIT_KERJA_SYNC_ACTIVE` (`true` / `false`)
- `MANAGE_UNIT_KERJA_APP_CENTER_URL` (`https://center-app.example.com`)

Contoh `.env`:

```dotenv
MANAGE_UNIT_KERJA_APP_ENV=production
MANAGE_UNIT_KERJA_CENTER_APPLICATION=false
MANAGE_UNIT_KERJA_SYNC_ACTIVE=true
MANAGE_UNIT_KERJA_APP_CENTER_URL=https://center-app.example.com
```

## Behavior CRUD
- `center_application = true` => CRUD full (create/edit/delete aktif).
- `app_env = local` => CRUD tetap aktif (developer/local mode).
- selain kondisi di atas => CRUD dibatasi; UI element Filament disembunyikan.

## Filament Resource
- `UnitKerjaResource` untuk `UnitKerja` model
- `ListUnitKerja` menyembunyikan `Create` jika CRUD tidak diizinkan.
- `UnitKerjaResourceTable` menonaktifkan aksi edit/restore/forceDelete jika CRUD tidak diizinkan.
- `UsersRelationManager` juga diperiksa `isCrudAllowed()` untuk attach/detach.

## API Sync
### Center
- Endpoint: `GET /api/manage-unit-kerja/center/provision`
- Menghasilkan JSON data `UnitKerja` dari database.
- Hanya tersedia ketika `center_application=true`.

### Client
- Endpoint: `POST /api/manage-unit-kerja/client/sync`
- Memanggil `GET {app_center_url}/api/manage-unit-kerja/center/provision`.
- Mengupdate/insert unit kerja berdasarkan `slug`.
- Hanya berjalan kalau `sync.active=true`.

### Contoh pemakaian client (curl)

```bash
curl -X POST \
  -H "Accept: application/json" \
  https://client-app.example.com/api/manage-unit-kerja/client/sync
```

## Command CLI
- `php artisan manage-unit-kerja:sync` (juga cek sync.active, placeholder).

## Pengembangan
- Implementasi provisioning/tokens/auth untuk keamanan.
- Tambahkan validasi payload (schema). 
- Buat queue/batch sync utuh jika volume besar.

---

## Catatan
Package ini didesain sebagai satu kesatuan komponen manajemen unit kerja dengan behavior client/center yang konsisten.
