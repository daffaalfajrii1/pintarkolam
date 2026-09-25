# PintarKolam

## Sistem Informasi Budidaya Ikan Air Tawar Rejang Lebong

> **Pantau Air, Atur Pakan, Panen Lebih Tepat**

Dokumen ini menjadi brief utama pengembangan aplikasi **PintarKolam**, yaitu platform digital untuk membantu pembudidaya ikan air tawar, khususnya ikan nila dan lele, mengurangi risiko gagal panen akibat kualitas air, kesalahan pemberian pakan, dan kurangnya pencatatan budidaya.

---

## 1. Tujuan Aplikasi

PintarKolam dikembangkan untuk:

1. Membantu pembudidaya memantau kualitas air kolam secara rutin.
2. Memberikan rekomendasi tindakan berdasarkan pH, suhu, DO, dan kondisi budidaya.
3. Membantu mengatur jadwal serta pencatatan pakan.
4. Menghasilkan estimasi waktu dan hasil panen.
5. Menyediakan riwayat perkembangan setiap kolam dan siklus budidaya.
6. Menghubungkan pembudidaya dengan buyer melalui katalog hasil ikan.
7. Menyediakan peta lokasi kolam dan pembudidaya.
8. Menyediakan data agregat yang dapat membantu pemerintah atau pengelola program perikanan.

---

## 2. Arsitektur Teknologi

### Backend dan Web

- Laravel 12.
- Laravel REST API dengan prefix `/api/v1`.
- Landing page publik menggunakan Blade.
- Dashboard admin menggunakan template **Duralux Admin** ada di root project.
- Dashboard pengguna menggunakan layout web yang konsisten dengan aplikasi.
- Breeze untuk autentikasi web berbasis session/cookie.
- Sanctum untuk autentikasi API Flutter menggunakan Bearer Token.
- Spatie Laravel Permission untuk role dan permission.
- MySQL atau MariaDB.
- Storage Laravel untuk foto kolam, foto ikan, dan dokumen pendukung.

### Mobile

- Flutter.
- Flutter hanya mengakses REST API Laravel.
- Autentikasi menggunakan Sanctum Bearer Token.
- FCM untuk push notification.
- `flutter_local_notifications` untuk notifikasi lokal.
- Dukungan pengambilan foto dari kamera dan galeri.
- Siapkan struktur agar input data dapat dikembangkan menjadi mode offline.

### Struktur Project

Gunakan satu project Laravel untuk:

```text
Landing Page
Dashboard Pengguna
Dashboard Admin
REST API Mobile
Database
```

Repository utama:

```text
pintarkolam
```

Domain yang dapat dipertimbangkan:

```text
pintarkolam.rejanglebongkab.go.id
```

---

## 3. Role dan Hak Akses

Gunakan role utama berikut:

### Admin

Admin memiliki akses untuk:

- Mengelola pengguna.
- Mengelola pembudidaya.
- Memverifikasi profil pembudidaya.
- Mengelola jenis ikan.
- Mengelola parameter kualitas air.
- Mengelola aturan rekomendasi.
- Mengelola data kolam dan peta.
- Memoderasi katalog hasil ikan.
- Mengelola artikel edukasi.
- Mengelola notifikasi dan pengumuman.
- Mengatur landing page dan feature flags.
- Melihat statistik budidaya secara agregat.

### Pembudidaya

Pembudidaya dapat:

- Mengelola profil usaha atau profil pribadi.
- Menambahkan kolam.
- Membuat siklus budidaya.
- Mengisi data tebar benih.
- Mencatat kualitas air.
- Melihat rekomendasi sistem.
- Mengatur dan mencatat pemberian pakan.
- Mencatat pertumbuhan ikan.
- Mencatat kematian atau masalah kesehatan ikan.
- Melihat estimasi panen.
- Menampilkan ikan siap jual di katalog.
- Menghubungi buyer melalui WhatsApp.

### Buyer

Buyer dapat:

- Mendaftar dan login.
- Melihat katalog ikan.
- Memfilter ikan berdasarkan jenis, ukuran, lokasi, dan ketersediaan.
- Melihat profil pembudidaya.
- Melihat lokasi perkiraan pembudidaya atau kolam.
- Menghubungi pembudidaya melalui WhatsApp.
- Menerima notifikasi ketika terdapat ikan baru yang tersedia.

### Status Etalase Pembudidaya

Jangan membuat role `seller` terpisah. Pembudidaya dapat mengaktifkan etalase dengan status:

```text
inactive
pending
approved
rejected
suspended
```

Admin tetap menjadi pihak yang memverifikasi dan mengaktifkan etalase.

---

## 4. Fitur Landing Page

Landing page harus dibuat modular karena desain atau template final belum ditentukan.

Struktur awal:

- Hero section PintarKolam.
- Penjelasan permasalahan kualitas air.
- Fitur utama aplikasi.
- Cara kerja PintarKolam.
- Statistik atau dampak program.
- Daftar pembudidaya atau produk unggulan.
- Artikel edukasi.
- Peta sebaran pembudidaya.
- Call to action untuk daftar sebagai pembudidaya atau buyer.
- Kontak dan informasi Pemerintah Kabupaten Rejang Lebong.

Admin dapat mengatur konten landing page melalui dashboard, jika modul tersebut diaktifkan.

Gunakan placeholder komponen dan data dinamis terlebih dahulu. Jangan mengunci aplikasi pada template landing page tertentu.

---

## 5. Fitur Inti Pembudidaya

### 5.1 Manajemen Kolam

Data kolam:

- Nama kolam.
- Jenis kolam: beton, terpal, tanah, bioflok, atau lainnya.
- Luas kolam.
- Kedalaman kolam.
- Estimasi volume air.
- Koordinat GPS.
- Foto kolam.
- Keterangan.
- Status kolam.

Status kolam:

```text
active
inactive
maintenance
```

### 5.2 Siklus Budidaya

Data siklus:

- Nama siklus.
- Kolam.
- Jenis ikan: nila atau lele.
- Tanggal tebar.
- Jumlah benih.
- Ukuran awal benih.
- Asal benih.
- Target ukuran panen.
- Target tanggal panen.
- Status siklus.
- Catatan.

Status siklus:

```text
preparation
active
near_harvest
completed
failed
```

### 5.3 Input Kualitas Air

Input minimal MVP:

- pH.
- Suhu air.
- DO atau oksigen terlarut.
- Waktu pengukuran.
- Kondisi visual air.
- Bau air.
- Catatan pembudidaya.
- Foto kondisi air, jika diperlukan.

Parameter lanjutan yang dapat ditambahkan:

- Amonia.
- Nitrit.
- Kekeruhan.
- Alkalinitas.
- Salinitas, jika dibutuhkan.

### 5.4 Indeks Kesehatan Kolam

Buat indikator sederhana dengan skor 0 sampai 100 berdasarkan data kualitas air terakhir.

Kategori:

```text
80-100  Normal
50-79   Waspada
0-49    Kritis
```

Indeks harus dapat ditelusuri. Tampilkan faktor yang memengaruhi nilai, misalnya:

- pH tidak sesuai.
- DO rendah.
- Suhu terlalu tinggi atau rendah.
- Pengukuran sudah terlalu lama.
- Terdapat catatan kematian ikan.

Nilai parameter dan bobot penilaian harus tersimpan di database agar dapat diubah admin tanpa mengubah kode program.

### 5.5 Rekomendasi Kualitas Air

Sistem memberikan rekomendasi berbasis aturan, bukan langsung menggunakan AI.

Contoh hasil rekomendasi:

- Kondisi air normal, lanjutkan pemantauan.
- Tambahkan aerasi.
- Lakukan penggantian air secara bertahap.
- Kurangi pemberian pakan sementara.
- Periksa kepadatan ikan.
- Lakukan pengukuran ulang.
- Periksa kemungkinan masalah kesehatan ikan.
- Hubungi admin atau penyuluh jika kondisi kritis.

Aturan rekomendasi harus dapat dikonfigurasi berdasarkan:

- Jenis ikan.
- Umur atau fase budidaya.
- Parameter yang diukur.
- Nilai minimum dan maksimum.
- Tingkat keparahan.
- Saran tindakan.

Catatan: parameter awal perlu divalidasi bersama penyuluh atau pihak teknis perikanan sebelum digunakan sebagai dasar rekomendasi resmi.

### 5.6 Jadwal dan Pencatatan Pakan

Fitur:

- Jadwal pakan harian.
- Waktu pemberian pakan.
- Jenis pakan.
- Jumlah pakan.
- Status sudah atau belum diberikan.
- Catatan sisa pakan.
- Riwayat pemberian pakan.
- Estimasi kebutuhan pakan.

Pengembangan lanjutan:

- Perhitungan berdasarkan biomassa.
- Berat rata-rata ikan.
- Jumlah ikan hidup.
- FCR.
- Efisiensi pakan.

### 5.7 Pertumbuhan dan Estimasi Panen

Data pertumbuhan:

- Tanggal sampling.
- Jumlah sampel.
- Berat rata-rata ikan.
- Panjang rata-rata ikan.
- Estimasi jumlah ikan hidup.
- Catatan perkembangan.

Estimasi panen menggunakan:

- Tanggal tebar.
- Jenis ikan.
- Jumlah benih.
- Berat rata-rata.
- Target ukuran.
- Survival rate.
- Riwayat pakan.
- Riwayat pertumbuhan.
- Catatan kematian.

Hasil estimasi:

- Perkiraan tanggal panen.
- Perkiraan jumlah ikan.
- Perkiraan berat total.
- Perkiraan nilai jual.
- Status kesiapan panen.

Gunakan formula yang transparan pada MVP. Machine learning dapat ditambahkan setelah tersedia data historis budidaya yang memadai.

### 5.8 Catatan Kesehatan Ikan

Pembudidaya dapat mencatat:

- Gejala yang terlihat.
- Jumlah ikan terdampak.
- Jumlah kematian.
- Foto ikan.
- Dugaan penyebab.
- Tindakan yang dilakukan.
- Status penanganan.

Fitur ini tidak boleh memberikan diagnosis medis otomatis pada tahap awal. Sistem cukup membantu pencatatan dan memberikan saran umum untuk pemeriksaan lebih lanjut.

---

## 6. Katalog Hasil Ikan

Pembudidaya dapat menampilkan hasil budidaya yang tersedia:

- Jenis ikan.
- Ukuran ikan.
- Estimasi stok.
- Harga per kilogram atau per ekor.
- Minimal pemesanan.
- Foto ikan.
- Lokasi.
- Deskripsi.
- Status tersedia atau habis.
- Nomor WhatsApp.

Buyer menggunakan tombol:

```text
Hubungi Pembudidaya via WhatsApp
```

MVP tidak mencakup:

- Payment gateway.
- Checkout.
- Keranjang belanja.
- Saldo pengguna.
- Ongkir otomatis.
- Chat internal.

---

## 7. Peta Lokasi Kolam

Fitur peta:

- Menampilkan sebaran pembudidaya.
- Menampilkan lokasi kolam.
- Filter berdasarkan jenis ikan.
- Filter berdasarkan ikan siap jual.
- Menampilkan profil pembudidaya.
- Menampilkan rute menuju lokasi.

Keamanan lokasi:

- Lokasi publik menggunakan titik perkiraan atau area desa.
- Koordinat lengkap hanya dapat dilihat oleh pemilik dan admin.
- Sediakan opsi bagi pembudidaya untuk menyembunyikan lokasi kolam.

---

## 8. Notifikasi

Gunakan FCM dan notifikasi lokal Flutter.

Jenis notifikasi:

- Kualitas air berada pada kondisi kritis.
- Jadwal pemberian pakan.
- Pengukuran kualitas air belum dilakukan.
- Siklus budidaya mendekati panen.
- Produk ikan baru tersedia.
- Etalase pembudidaya disetujui atau ditolak.
- Pengumuman dari admin.

Endpoint:

```text
POST   /api/v1/notifications/device-token
DELETE /api/v1/notifications/device-token
GET    /api/v1/notifications
PUT    /api/v1/notifications/preferences
```

Flutter harus menangani notifikasi pada kondisi foreground, background, dan terminated serta mendukung deep-link ke detail kolam, rekomendasi, atau produk.

---

## 9. Dashboard Admin Duralux

Gunakan **Duralux Admin** sebagai template dashboard admin.

Modul dashboard:

1. Dashboard ringkasan.
2. Manajemen pengguna.
3. Verifikasi pembudidaya.
4. Data kolam.
5. Data siklus budidaya.
6. Monitoring kualitas air.
7. Master jenis ikan.
8. Master parameter kualitas air.
9. Aturan rekomendasi.
10. Jadwal dan pencatatan pakan.
11. Estimasi dan realisasi panen.
12. Moderasi katalog ikan.
13. Peta pembudidaya dan kolam.
14. Artikel edukasi.
15. Notifikasi dan pengumuman.
16. Pengaturan landing page.
17. Feature flags.
18. Laporan dan export data.

Widget statistik awal:

- Total pengguna.
- Total pembudidaya terverifikasi.
- Total kolam aktif.
- Total siklus berjalan.
- Kolam dengan status kritis.
- Siklus mendekati panen.
- Total produk ikan tersedia.
- Sebaran pembudidaya per kecamatan.

---

## 10. Struktur Database Utama

```text
users
roles
permissions
farmer_profiles
fish_species
ponds
pond_photos
cultivation_cycles
water_quality_parameters
water_quality_logs
pond_health_scores
recommendations
recommendation_rules
feeding_schedules
feeding_logs
growth_records
mortality_logs
fish_health_logs
harvest_estimates
harvest_records
products
product_photos
locations
notifications
device_tokens
articles
settings
feature_flags
activity_logs
```

Gunakan foreign key, index pada kolom pencarian, soft delete pada data yang memerlukan riwayat, Form Request, API Resource, Policy, dan pagination.

---

## 11. Struktur API Awal

### Auth

```text
POST /api/v1/auth/register
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/me
PUT  /api/v1/auth/password
```

### Data Publik

```text
GET /api/v1/home
GET /api/v1/species
GET /api/v1/products
GET /api/v1/products/{product}
GET /api/v1/farmers
GET /api/v1/map/ponds
GET /api/v1/blog
GET /api/v1/blog/{post}
```

### Pembudidaya

```text
GET    /api/v1/my/profile
PUT    /api/v1/my/profile
GET    /api/v1/my/ponds
POST   /api/v1/my/ponds
GET    /api/v1/my/ponds/{pond}
PUT    /api/v1/my/ponds/{pond}
DELETE /api/v1/my/ponds/{pond}
```

### Siklus Budidaya

```text
GET  /api/v1/cycles
POST /api/v1/cycles
GET  /api/v1/cycles/{cycle}
PUT  /api/v1/cycles/{cycle}

GET  /api/v1/cycles/{cycle}/water-quality
POST /api/v1/cycles/{cycle}/water-quality

GET  /api/v1/cycles/{cycle}/recommendations
GET  /api/v1/cycles/{cycle}/feeding-schedules
POST /api/v1/cycles/{cycle}/feeding-logs
GET  /api/v1/cycles/{cycle}/growth-records
POST /api/v1/cycles/{cycle}/growth-records
GET  /api/v1/cycles/{cycle}/harvest-estimate
```

### Produk

```text
GET    /api/v1/products
POST   /api/v1/products
GET    /api/v1/products/{product}
PUT    /api/v1/products/{product}
DELETE /api/v1/products/{product}
```

### Admin

```text
GET /api/v1/admin/dashboard
GET /api/v1/admin/users
GET /api/v1/admin/farmers/pending
GET /api/v1/admin/reports
```

Gunakan response envelope:

```json
{
  "success": true,
  "message": "Data berhasil diambil",
  "data": {},
  "meta": {}
}
```

---

## 12. Fitur Unggulan untuk Lomba Inovasi

Fitur yang menjadi nilai jual utama PintarKolam:

### Smart Water Recommendation

Sistem menerjemahkan data pH, suhu, dan DO menjadi status serta tindakan yang mudah dipahami pembudidaya.

### Indeks Kesehatan Kolam

Satu skor ringkas yang menunjukkan kondisi kolam dan faktor penyebabnya.

### Early Warning System

Peringatan dini ketika kualitas air menurun atau pembudidaya terlambat melakukan pemantauan.

### Digital Cultivation Diary

Seluruh aktivitas budidaya tersimpan sebagai riwayat digital, bukan hanya catatan manual.

### Estimasi Panen

Pembudidaya dapat memperkirakan kapan ikan siap dipanen dan berapa potensi hasilnya.

### Peta Ekosistem Perikanan

Data lokasi pembudidaya membantu melihat potensi perikanan air tawar di Rejang Lebong.

### Katalog Hasil Budidaya Lokal

Buyer dapat menemukan ikan nila dan lele lokal tanpa marketplace yang kompleks.

### Dashboard Data Pemerintah

Admin dapat melihat jumlah kolam, siklus aktif, kondisi kualitas air, dan potensi panen secara agregat.

---

## 13. Roadmap Pengembangan

### Tahap 1 — MVP

- Setup Laravel dan database.
- Auth web dan API.
- Role admin, pembudidaya, dan buyer.
- Integrasi Duralux Admin.
- Landing page sementara yang modular.
- CRUD kolam.
- CRUD siklus budidaya.
- Input pH, suhu, dan DO.
- Rekomendasi berbasis aturan.
- Jadwal pakan.
- Estimasi panen sederhana.
- Katalog ikan.
- Tombol WhatsApp.
- Peta lokasi.

### Tahap 2 — Mobile dan Notifikasi

- Aplikasi Flutter.
- Login Bearer Token.
- Input kualitas air melalui mobile.
- Pengambilan foto kolam dan ikan.
- Push notification.
- Pengingat pakan.
- Notifikasi kualitas air kritis.
- Notifikasi produk baru.

### Tahap 3 — Pelaporan dan Pemerintah

- Grafik kualitas air.
- Laporan budidaya PDF.
- Export Excel.
- Statistik per kecamatan atau desa.
- Dashboard potensi panen.
- Rekap pembudidaya terverifikasi.

### Tahap 4 — Inovasi Lanjutan

- Sensor IoT pH, suhu, dan DO.
- Integrasi data cuaca.
- Deteksi penyakit ikan berbasis AI.
- Prediksi panen berbasis machine learning.
- Prediksi risiko kematian ikan.
- Mode offline untuk area dengan koneksi terbatas.
- QR Code identitas kolam.

---

## 14. Aturan Pengembangan untuk Codex

1. Jangan membuat marketplace kompleks pada MVP.
2. Jangan menambahkan payment gateway, checkout, keranjang, saldo, atau ongkir otomatis.
3. Gunakan Laravel sebagai sumber utama seluruh business logic.
4. Flutter hanya mengakses API Laravel.
5. Pisahkan autentikasi web Breeze dan API Sanctum.
6. Gunakan `api/v1` sejak awal.
7. Gunakan Duralux Admin untuk dashboard admin.
8. Jangan mengganti Duralux dengan template admin lain.
9. Landing page harus modular karena desain final belum dipilih.
10. Gunakan Policy agar pembudidaya hanya dapat mengakses data miliknya.
11. Gunakan Form Request untuk validasi.
12. Gunakan API Resource untuk response API.
13. Gunakan pagination pada daftar data.
14. Simpan aturan kualitas air di database agar dapat diubah admin.
15. Semua fitur penting harus memiliki migration, model, controller, request, resource, policy, route, dan test.
16. Gunakan seed data untuk admin, jenis ikan nila dan lele, parameter kualitas air, serta contoh rekomendasi.
17. Tampilkan pesan error API yang konsisten dan mudah dipahami.
18. Pastikan upload foto menggunakan validasi ukuran dan tipe file.
19. Lindungi koordinat kolam dan data pribadi pembudidaya.
20. Gunakan Bahasa Indonesia pada label utama aplikasi.

---

## 15. Definisi Selesai MVP

MVP dianggap selesai apabila:

- Admin dapat login ke dashboard Duralux.
- Admin dapat mengelola pengguna dan pembudidaya.
- Pembudidaya dapat membuat kolam dan siklus budidaya.
- Pembudidaya dapat menginput kualitas air.
- Sistem dapat memberikan status dan rekomendasi.
- Sistem dapat membuat jadwal pakan.
- Sistem dapat menampilkan estimasi panen.
- Pembudidaya dapat membuat katalog ikan.
- Buyer dapat melihat katalog dan menghubungi pembudidaya melalui WhatsApp.
- Peta lokasi dapat menampilkan data kolam secara aman.
- API dapat digunakan oleh Flutter.
- Flutter dapat login, melihat dashboard, menginput data, dan menerima notifikasi.
- Data dapat dimoderasi oleh admin.
- Landing page dapat menampilkan informasi PintarKolam.

