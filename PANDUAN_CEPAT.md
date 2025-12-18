# 🚀 PANDUAN CEPAT - Setup Sistem Penggajian

## ⚡ Quick Start (5 Menit)

### 1. Buat Database
```bash
mysql -u root -p
```
Lalu jalankan:
```sql
CREATE DATABASE kaw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 2. Sesuaikan Password Database
Edit file `.env`, ubah baris ini sesuai password MySQL Anda:
```env
DB_PASSWORD=your_mysql_password
```

### 3. Install & Setup
```bash
# Install dependencies
composer install

# Generate application key
php artisan key:generate

# Jalankan migrasi dan seeder (buat tabel + data awal)
php artisan migrate:fresh --seed

# Buat symbolic link untuk storage (untuk upload foto)
php artisan storage:link

# Jalankan server
php artisan serve
```

### 4. Login
Buka browser: **http://localhost:8000**

**Login sebagai Admin:**
- Email: `admin@gmail.com`
- Password: `admin123`

**Login sebagai Manager:**
- Email: `manager@gmail.com`
- Password: `manager123`

**Login sebagai Karyawan:**
- Email: `karyawan1@gmail.com`
- Password: `karyawan123`

---

## 📖 Cara Menggunakan Sistem

### A. Sebagai ADMIN

#### 1. Manajemen Jabatan
- Klik menu **"Jabatan"**
- Klik **"Tambah Jabatan"**
- Isi nama jabatan dan gaji pokok
- Klik **"Simpan"**

#### 2. Manajemen Karyawan
- Klik menu **"Karyawan"**
- Klik **"Tambah Karyawan"**
- Isi data akun (email, password, role)
- Isi data karyawan (NIP, nama, jabatan, dll)
- Upload foto (opsional)
- Klik **"Simpan"**

#### 3. Buat Gaji
- Klik menu **"Manajemen Gaji"**
- Klik **"Tambah Gaji"**
- Pilih karyawan dan periode (bulan/tahun)
- Gaji pokok otomatis terisi
- Tambah bonus (opsional):
  - Klik **"Tambah Bonus"**
  - Isi nama bonus dan jumlah
- Tambah potongan (opsional):
  - Klik **"Tambah Potongan"**
  - Isi nama potongan dan jumlah (misal: BPJS, Pajak)
- Sistem otomatis menghitung gaji bersih
- Klik **"Simpan"**

#### 4. Approve Gaji
- Di halaman **"Manajemen Gaji"**
- Cari gaji dengan status **"Draft"**
- Klik tombol **centang hijau** untuk approve
- Status berubah menjadi **"Disetujui"**

#### 5. Bayar Gaji
- Di halaman **"Manajemen Gaji"**
- Cari gaji dengan status **"Disetujui"**
- Klik tombol **"Bayar"**
- Status berubah menjadi **"Dibayar"**

### B. Sebagai MANAGER

Manager memiliki akses yang sama dengan Admin, **KECUALI**:
- Manager **TIDAK BISA** menandai gaji sebagai dibayar
- Manager hanya bisa approve gaji

### C. Sebagai KARYAWAN

#### 1. Lihat Dashboard
- Setelah login, Anda akan melihat:
  - Profil Anda
  - Gaji pokok
  - Total gaji yang sudah diterima
  - Riwayat gaji

#### 2. Lihat Slip Gaji
- Klik menu **"Slip Gaji Saya"**
- Anda akan melihat semua slip gaji Anda
- Klik **"Lihat Detail"** untuk melihat slip lengkap
- Klik **"Cetak"** untuk mencetak slip gaji

---

## 🎯 Alur Kerja Lengkap

```
1. ADMIN membuat Jabatan
   ↓
2. ADMIN/MANAGER menambah Karyawan
   ↓
3. ADMIN/MANAGER membuat Gaji (status: Draft)
   ↓
4. MANAGER/ADMIN approve Gaji (status: Disetujui)
   ↓
5. ADMIN tandai Gaji Dibayar (status: Dibayar)
   ↓
6. KARYAWAN lihat Slip Gaji
```

---

## 📊 Fitur-Fitur Utama

### ✅ Dashboard
- **Admin**: Statistik lengkap (total karyawan, gaji draft, dll)
- **Manager**: Fokus pada approval gaji
- **Karyawan**: Profil dan riwayat gaji

### ✅ Manajemen Jabatan
- Tambah, edit, hapus jabatan
- Set gaji pokok per jabatan
- Lihat jumlah karyawan per jabatan

### ✅ Manajemen Karyawan
- Tambah karyawan dengan akun user
- Upload foto karyawan
- Edit data karyawan
- Set status aktif/nonaktif
- Lihat riwayat gaji karyawan

### ✅ Manajemen Gaji
- Buat gaji dengan bonus dan potongan dinamis
- Filter berdasarkan status, bulan, tahun
- Approve gaji (Manager/Admin)
- Tandai sebagai dibayar (Admin only)
- Validasi duplikasi (1 karyawan 1 gaji per bulan)

### ✅ Slip Gaji
- Tampilan profesional
- Detail lengkap (gaji pokok, bonus, potongan)
- Fitur cetak (print-friendly)
- Status approval

---

## 🔐 Keamanan

- ✅ Password di-hash dengan bcrypt
- ✅ Role-based access control
- ✅ CSRF protection
- ✅ Validasi input lengkap
- ✅ Middleware protection

---

## 💡 Tips & Trik

### 1. Menambah Bonus/Potongan Umum
Contoh bonus yang sering digunakan:
- Bonus Kehadiran
- Bonus Kinerja
- Bonus Lembur
- Tunjangan Transportasi
- Tunjangan Makan

Contoh potongan yang sering digunakan:
- BPJS Kesehatan
- BPJS Ketenagakerjaan
- Pajak PPh 21
- Potongan Terlambat
- Potongan Pinjaman

### 2. Mengedit Gaji
- Gaji hanya bisa diedit jika statusnya masih **"Draft"**
- Setelah disetujui, gaji tidak bisa diedit lagi
- Jika perlu mengubah, hapus dan buat ulang

### 3. Menghapus Data
- Jabatan tidak bisa dihapus jika masih ada karyawan yang menggunakannya
- Menghapus karyawan akan menghapus semua data gaji karyawan tersebut
- Gaji hanya bisa dihapus jika statusnya masih **"Draft"**

### 4. Upload Foto
- Format yang didukung: JPG, JPEG, PNG
- Maksimal ukuran: 2MB
- Foto disimpan di folder `storage/app/public/karyawan`

---

## ❓ FAQ

**Q: Bagaimana cara mengubah password karyawan?**
A: Edit karyawan, isi field password baru. Kosongkan jika tidak ingin mengubah.

**Q: Bisa tidak 1 karyawan dapat 2 gaji dalam 1 bulan?**
A: Tidak bisa. Sistem akan validasi duplikasi.

**Q: Bagaimana cara mencetak slip gaji?**
A: Buka slip gaji, klik tombol "Cetak", lalu gunakan Ctrl+P atau Print dari browser.

**Q: Karyawan bisa lihat gaji karyawan lain?**
A: Tidak. Karyawan hanya bisa lihat slip gaji sendiri.

**Q: Manager bisa bayar gaji?**
A: Tidak. Hanya Admin yang bisa menandai gaji sebagai dibayar.

---

## 🆘 Troubleshooting

### Error: "Unknown database 'kaw'"
**Solusi**: Buat database dulu dengan perintah SQL di atas.

### Error: "Access denied for user 'root'"
**Solusi**: Periksa password MySQL di file `.env`.

### Error: "Class not found"
**Solusi**: Jalankan `composer dump-autoload`.

### Foto tidak muncul
**Solusi**: Jalankan `php artisan storage:link`.

### Error saat migrasi
**Solusi**: 
```bash
# Reset database dan jalankan ulang
php artisan migrate:fresh --seed
```

---

## 📞 Bantuan

Jika ada masalah atau pertanyaan, silakan:
1. Baca dokumentasi lengkap di `SISTEM_PENGGAJIAN.md`
2. Baca ringkasan di `RINGKASAN.md`
3. Hubungi developer

---

**Selamat menggunakan Sistem Penggajian! 🎉**
