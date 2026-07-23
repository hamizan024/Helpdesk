# Skill: run-dev

Jalankan environment development IT Helpdesk.

## Stack yang berjalan

| Service | Tool | URL |
|---------|------|-----|
| Web server | Laragon (Apache) | http://helpdesk.test |
| Database | Laragon (MySQL) | localhost:3306 |
| Asset bundler | Vite (npm) | hot-reload di port 5173 |

## Langkah

### 1. Pastikan Laragon aktif
Laragon harus berjalan (Apache + MySQL aktif di system tray).

### 2. Jalankan Vite dev server
```bash
cd c:/laragon/www/helpdesk
npm run dev
```
Biarkan terminal ini tetap terbuka. Vite akan HMR otomatis saat file CSS/JS berubah.

### 3. Akses di browser
- **Utama:** http://helpdesk.test
- **Alternatif:** http://localhost:8000 (jika pakai `php artisan serve`)

## Perintah berguna lainnya

```bash
# Jalankan via artisan serve (tanpa Laragon)
php artisan serve

# Build production
npm run build

# Cek route yang terdaftar
php artisan route:list

# Clear semua cache
php artisan optimize:clear
```

## Credential default (seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | (lihat DatabaseSeeder) | password |
| Teknisi | teknisi@example.com | password |
| User | user@example.com | password |

## Catatan
- `APP_URL` di `.env` saat ini `http://localhost` — URL avatar menggunakan path relatif `/storage/...` sehingga bekerja di semua hostname.
- Storage symlink ada di `public/storage` → `storage/app/public` (Windows Junction).