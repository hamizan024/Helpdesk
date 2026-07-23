# Skill: db-reset

Reset dan seed ulang database IT Helpdesk.

## Perintah

### Reset penuh + seed
```bash
php artisan migrate:fresh --seed
```
Ini akan:
1. Drop semua tabel
2. Jalankan ulang semua migration (29 migration)
3. Jalankan `DatabaseSeeder` → `MasterDataSeeder`

### Hanya seed ulang (tanpa drop tabel)
```bash
php artisan db:seed
```

### Jalankan migration saja (tanpa seed)
```bash
php artisan migrate
```

### Rollback migration terakhir
```bash
php artisan migrate:rollback
```

## Seeder yang ada

| Seeder | Isi |
|--------|-----|
| `DatabaseSeeder` | User admin test, panggil MasterDataSeeder |
| `MasterDataSeeder` | Status, Priority, Category, Department default |

## Data master default (dari seeder)

**Status:** Open, In Progress, Closed  
**Priority:** High, Medium, Low  
**Category:** Hardware, Software, Network, Lainnya  
**Department:** IT, HR, Finance, Operations

## Cek status migration
```bash
php artisan migrate:status
```

## Buat migration baru
```bash
php artisan make:migration add_column_to_table_name --table=table_name
```

## Catatan
- Database: MySQL via Laragon, database name ada di `.env` key `DB_DATABASE`
- Setelah `migrate:fresh`, storage avatar di `storage/app/public/avatars/` **tidak ikut terhapus** — hanya data DB yang di-reset.
- Jika ada error "SQLSTATE Table already exists", jalankan `migrate:fresh` bukan `migrate`.