# Skill: add-module

Scaffold fitur/modul baru di IT Helpdesk mengikuti pola yang sudah ada.

## Struktur modul (contoh: modul "Report")

```
app/
  Http/Controllers/Report/ReportController.php   ← namespace per modul
  Services/ReportService.php                     ← business logic
  Models/Report.php                              ← jika butuh model baru
  Policies/ReportPolicy.php                      ← jika butuh authorization

resources/views/
  reports/
    index.blade.php
    show.blade.php
    create.blade.php
    edit.blade.php

database/migrations/
  xxxx_create_reports_table.php
```

## Langkah-langkah

### 1. Buat Model + Migration
```bash
php artisan make:model Report -m
```

### 2. Buat Controller di namespace yang benar
```bash
php artisan make:controller Report/ReportController --resource
```
Ubah namespace di file: `App\Http\Controllers\Report`

### 3. Buat Service
Buat file `app/Services/ReportService.php`:
```php
namespace App\Services;

class ReportService
{
    public function getAll(User $user): Collection { ... }
}
```
Inject ke controller via constructor: `public function __construct(private ReportService $reportService)`

### 4. Daftarkan route di `routes/web.php`
```php
// Di dalam Route::middleware(['auth'])->group(...)
Route::resource('reports', ReportController::class);

// Jika admin only:
Route::middleware('admin')->group(function () {
    Route::resource('reports', ReportController::class);
});
```

### 5. Buat views mengikuti pola yang ada
- Extend `layouts.app`
- Gunakan `<x-app-card>` untuk semua konten
- Gunakan `<x-form-input>`, `<x-form-select>` untuk form
- Gunakan `<x-ticket-status>` style untuk badge status
- Gunakan `@section('title', 'Nama Halaman')`

### 6. Tambahkan menu di sidebar (jika perlu)
Edit `resources/views/layouts/app.blade.php`, tambahkan nav item di bagian yang sesuai:
```blade
<li class="nav-item">
    <a href="{{ route('reports.index') }}" class="nav-link ...">
        <span class="material-icons-round">assessment</span>
        <span class="nav-link-text">Reports</span>
    </a>
</li>
```

### 7. Authorization (opsional)
```bash
php artisan make:policy ReportPolicy --model=Report
```
Daftarkan di `app/Providers/AppServiceProvider.php`:
```php
Gate::policy(Report::class, ReportPolicy::class);
```

### 8. ActivityLog (jika modul terkait ticket)
Tambahkan di controller setiap kali ada aksi penting:
```php
ActivityLog::create([
    'ticket_id'   => $ticket->id,
    'user_id'     => auth()->id(),
    'action'      => 'action_name',
    'description' => 'Deskripsi aktivitas',
]);
```

## Konvensi penamaan

| Elemen | Format | Contoh |
|--------|--------|--------|
| Controller | PascalCase + Controller | `ReportController` |
| Namespace | `App\Http\Controllers\{Modul}` | `App\Http\Controllers\Report` |
| Service | PascalCase + Service | `ReportService` |
| Route name | snake_case | `reports.index` |
| View folder | lowercase plural | `resources/views/reports/` |
| Model | PascalCase singular | `Report` |
| Table | snake_case plural | `reports` |

## Role-based logic

```php
// Di controller — filter data berdasarkan role
$user = auth()->user();
if ($user->isAdmin()) {
    $data = $this->service->getAll();
} elseif ($user->isTechnician()) {
    $data = $this->service->getAssignedTo($user);
} else {
    $data = $this->service->getOwnedBy($user);
}
```

## Checklist sebelum selesai

- [ ] Migration berjalan: `php artisan migrate`
- [ ] Route terdaftar: `php artisan route:list --name=reports`
- [ ] Komponen Blade digunakan konsisten (`x-app-card`, badge, form inputs)
- [ ] `$fillable` pada model sudah lengkap
- [ ] ActivityLog ditambahkan untuk aksi penting
- [ ] Menu sidebar sudah ditambahkan (jika diperlukan)