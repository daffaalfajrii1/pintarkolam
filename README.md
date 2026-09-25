# PintarKolam (Laravel)

Sistem informasi budidaya ikan air tawar Rejang Lebong.

> Pantau Air, Atur Pakan, Panen Lebih Tepat

Fokus saat ini: **Laravel API + Landing + Dashboard Admin (Duralux) + Dashboard Pengguna**. Aplikasi Flutter ditunda.

## Stack

- Laravel 12
- Auth web: Laravel Breeze (session/cookie)
- Auth API: Laravel Sanctum (Bearer Token)
- Role/permission: Spatie Laravel Permission (`admin`, `pembudidaya`, `buyer`)
- UI admin/user: template **Duralux Admin** (`duralux-admin/` → `public/duralux`)
- Database: MySQL `pintarkolam`

## Kredensial database (.env)

Default lokal yang dipakai:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pintarkolam
DB_USERNAME=root
DB_PASSWORD=
```

Jika MySQL Anda memakai password, ubah `DB_PASSWORD` (mis. `root`).

## Akun demo (setelah seed)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@pintarkolam.id | password |
| Pembudidaya | pembudidaya@pintarkolam.id | password |
| Buyer | buyer@pintarkolam.id | password |

## Cara menjalankan

```powershell
cd d:\pintar-kolam
composer install
# pastikan .env DB_* benar
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

URL penting:

- Landing: http://127.0.0.1:8000
- Login: http://127.0.0.1:8000/login
- Dashboard user: http://127.0.0.1:8000/dashboard
- Admin Duralux: http://127.0.0.1:8000/admin
- API: http://127.0.0.1:8000/api/v1

## Endpoint API utama

```text
POST   /api/v1/auth/register
POST   /api/v1/auth/login
POST   /api/v1/auth/logout
GET    /api/v1/auth/me
PUT    /api/v1/auth/password

GET    /api/v1/home
GET    /api/v1/species
GET    /api/v1/products
GET    /api/v1/products/{id}
GET    /api/v1/farmers
GET    /api/v1/map/ponds
GET    /api/v1/blog
GET    /api/v1/blog/{slug}

GET|POST|PUT|DELETE /api/v1/my/ponds...
GET|PUT             /api/v1/my/profile
GET|POST|PUT        /api/v1/cycles...
POST                /api/v1/cycles/{id}/water-quality
GET                 /api/v1/cycles/{id}/recommendations
GET                 /api/v1/cycles/{id}/feeding-schedules
POST                /api/v1/cycles/{id}/feeding-logs
GET|POST            /api/v1/cycles/{id}/growth-records
GET                 /api/v1/cycles/{id}/harvest-estimate
POST|PUT|DELETE     /api/v1/products...
POST|DELETE|GET|PUT /api/v1/notifications...

GET /api/v1/admin/dashboard
GET /api/v1/admin/users
GET /api/v1/admin/farmers/pending
GET /api/v1/admin/reports
```

## Struktur utama

```text
app/Http/Controllers/Api/V1   # REST API
app/Http/Controllers/Admin    # Dashboard admin web
app/Http/Controllers/User     # Dashboard pembudidaya web
app/Models + Services         # Domain + skor air + estimasi panen
duralux-admin/                # Sumber template admin
public/duralux/               # Aset Duralux (junction)
resources/views/admin|user|landing
routes/api.php + routes/web.php
database/migrations + seeders
```

## Catatan

- Flutter sengaja belum dikerjakan / sudah dihapus dari repo untuk fokus Laravel.
- FCM push, laporan PDF/Excel, dan modul admin lanjutan mengikuti roadmap brief.
