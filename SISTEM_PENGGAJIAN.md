# Sistem Penggajian dengan AdminLTE

Sistem penggajian berbasis web menggunakan Laravel 12 dengan template AdminLTE. Sistem ini memiliki 3 role: **Admin**, **Manager**, dan **Karyawan**.

## 🎯 Fitur Utama

### 1. **Role Admin**
- Manajemen lengkap jabatan, karyawan, dan gaji
- Approve dan tandai gaji sebagai dibayar
- Akses penuh ke semua fitur sistem
- Dashboard dengan statistik lengkap

### 2. **Role Manager**
- Manajemen jabatan dan karyawan
- Approve gaji karyawan
- Lihat dashboard dan statistik
- Akses slip gaji semua karyawan

### 3. **Role Karyawan**
- Lihat slip gaji sendiri
- Download/cetak slip gaji
- Dashboard profil dan riwayat gaji

## 📦 Teknologi yang Digunakan

- **Framework**: Laravel 12
- **PHP**: 8.2
- **Database**: MySQL
- **Template**: AdminLTE 3.2
- **Frontend**: Bootstrap 4, jQuery, Font Awesome

## 🚀 Cara Instalasi

### 1. **Persiapan Database**

Buat database MySQL dengan nama `kaw`:

```bash
mysql -u root -p
```

Kemudian jalankan:

```sql
CREATE DATABASE kaw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 2. **Konfigurasi Environment**

File `.env` sudah ada. Pastikan konfigurasi database sesuai:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kaw
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Catatan**: Sesuaikan `DB_PASSWORD` dengan password MySQL Anda.

### 3. **Install Dependencies**

```bash
composer install
```

### 4. **Generate Application Key**

```bash
php artisan key:generate
```

### 5. **Jalankan Migrasi dan Seeder**

```bash
php artisan migrate:fresh --seed
```

Perintah ini akan:
- Membuat semua tabel database
- Mengisi data awal (jabatan dan user demo)

### 6. **Create Storage Link**

```bash
php artisan storage:link
```

### 7. **Jalankan Aplikasi**

```bash
php artisan serve
```

Aplikasi akan berjalan di: `http://localhost:8000`

## 👤 Akun Demo

Setelah seeder dijalankan, Anda dapat login dengan akun berikut:

### Admin
- **Email**: admin@gmail.com
- **Password**: admin123

### Manager
- **Email**: manager@gmail.com
- **Password**: manager123

### Karyawan
- **Email**: karyawan1@gmail.com (sampai karyawan5@gmail.com)
- **Password**: karyawan123

## 📊 Struktur Database

### Tabel Utama:

1. **users** - Data user dan role
2. **jabatan** - Data jabatan dan gaji pokok
3. **karyawan** - Data lengkap karyawan
4. **gaji** - Data gaji bulanan
5. **bonus** - Detail bonus gaji
6. **potongan** - Detail potongan gaji

### Relasi:
- User → Karyawan (One to One)
- Jabatan → Karyawan (One to Many)
- Karyawan → Gaji (One to Many)
- Gaji → Bonus (One to Many)
- Gaji → Potongan (One to Many)

## 📝 Alur Kerja Sistem

### 1. **Manajemen Jabatan** (Admin/Manager)
- Tambah, edit, hapus jabatan
- Set gaji pokok per jabatan

### 2. **Manajemen Karyawan** (Admin/Manager)
- Tambah karyawan baru dengan akun user
- Edit data karyawan
- Upload foto karyawan
- Set status aktif/nonaktif

### 3. **Proses Penggajian** (Admin/Manager)

**Langkah-langkah:**

1. **Buat Gaji** (Status: Draft)
   - Pilih karyawan dan periode
   - Gaji pokok otomatis dari jabatan
   - Tambah bonus (opsional)
   - Tambah potongan (opsional)
   - Sistem otomatis hitung gaji bersih

2. **Approve Gaji** (Status: Disetujui)
   - Manager/Admin approve gaji
   - Gaji tidak bisa diedit lagi

3. **Bayar Gaji** (Status: Dibayar) - Khusus Admin
   - Admin tandai gaji sebagai dibayar
   - Karyawan bisa lihat slip gaji

### 4. **Slip Gaji** (Semua Role)
- Karyawan: Lihat slip gaji sendiri
- Admin/Manager: Lihat semua slip gaji
- Fitur cetak slip gaji

## 🎨 Fitur Tambahan

### Dashboard Dinamis
- Dashboard berbeda untuk setiap role
- Statistik real-time
- Grafik dan chart (bisa dikembangkan)

### Filter dan Pencarian
- Filter gaji berdasarkan status, bulan, tahun
- Pencarian karyawan

### Validasi
- Validasi duplikasi gaji (1 karyawan 1 gaji per bulan)
- Validasi NIP unik
- Validasi email unik

### Security
- Middleware role-based access control
- Password hashing
- CSRF protection

## 📁 Struktur File Penting

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── JabatanController.php
│   │   ├── KaryawanController.php
│   │   └── GajiController.php
│   └── Middleware/
│       └── CheckRole.php
├── Models/
│   ├── User.php
│   ├── Jabatan.php
│   ├── Karyawan.php
│   ├── Gaji.php
│   ├── Bonus.php
│   └── Potongan.php
database/
├── migrations/
│   ├── 2024_01_01_000001_create_jabatan_table.php
│   ├── 2024_01_01_000002_add_role_to_users_table.php
│   ├── 2024_01_01_000003_create_karyawan_table.php
│   ├── 2024_01_01_000004_create_gaji_table.php
│   ├── 2024_01_01_000005_create_bonus_table.php
│   └── 2024_01_01_000006_create_potongan_table.php
└── seeders/
    └── DatabaseSeeder.php
resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    ├── auth/
    │   └── login.blade.php
    ├── dashboard/
    │   ├── admin.blade.php
    │   ├── manager.blade.php
    │   └── karyawan.blade.php
    ├── jabatan/
    ├── karyawan/
    └── gaji/
```

## 🔧 Troubleshooting

### Error: Unknown database 'kaw'
**Solusi**: Buat database terlebih dahulu dengan perintah SQL di atas.

### Error: Access denied for user 'root'
**Solusi**: Pastikan password MySQL di file `.env` sudah benar.

### Error: Class not found
**Solusi**: Jalankan `composer dump-autoload`

### Foto tidak muncul
**Solusi**: Jalankan `php artisan storage:link`

## 📞 Support

Jika ada pertanyaan atau masalah, silakan hubungi developer atau buat issue di repository.

## 📄 License

MIT License - Bebas digunakan untuk keperluan apapun.

---

**Dibuat dengan ❤️ menggunakan Laravel & AdminLTE**
