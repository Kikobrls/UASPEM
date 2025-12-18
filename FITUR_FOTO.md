# 📸 UPDATE: Fitur Foto di CRUD Karyawan

## ✅ Fitur Foto yang Sudah Ditambahkan

### 1. **Tampilan Foto di Tabel Index (Daftar Karyawan)**
- ✅ Foto karyawan tampil sebagai thumbnail (50x50px) di kolom pertama tabel
- ✅ Jika karyawan belum upload foto, akan tampil avatar otomatis dari UI Avatars
- ✅ Avatar otomatis menggunakan nama karyawan dengan warna random

### 2. **Preview Foto di Form Create (Tambah Karyawan)**
- ✅ Preview foto real-time saat memilih file
- ✅ Preview muncul sebelum form di-submit
- ✅ Ukuran preview: 150x150px
- ✅ Validasi format: JPG, JPEG, PNG
- ✅ Maksimal ukuran: 2MB

### 3. **Preview Foto di Form Edit (Edit Karyawan)**
- ✅ Foto lama ditampilkan jika sudah ada
- ✅ Preview foto baru real-time saat memilih file baru
- ✅ Foto lama akan diganti dengan preview foto baru
- ✅ Jika tidak pilih foto baru, foto lama tetap digunakan

### 4. **Detail Karyawan (Show)**
- ✅ Foto besar ditampilkan di profil karyawan
- ✅ Jika tidak ada foto, tampil placeholder

---

## 🎨 Tampilan Foto

### **Di Tabel Index:**
```
┌────────┬──────┬────────────┬──────────┐
│  Foto  │ NIP  │    Nama    │ Jabatan  │
├────────┼──────┼────────────┼──────────┤
│ [IMG]  │ 001  │ John Doe   │ Manager  │
│ [AVT]  │ 002  │ Jane Smith │ Staff    │
└────────┴──────┴────────────┴──────────┘
```
- `[IMG]` = Foto yang sudah diupload
- `[AVT]` = Avatar otomatis dengan inisial nama

### **Di Form Create/Edit:**
```
┌─────────────────────────┐
│                         │
│    [Preview Image]      │
│      150 x 150px        │
│                         │
└─────────────────────────┘
[Choose File] nama_file.jpg
Format: JPG, JPEG, PNG. Maksimal 2MB
```

---

## 🚀 Cara Menggunakan

### **Upload Foto Baru:**
1. Login sebagai Admin/Manager
2. Klik menu **"Karyawan"**
3. Klik **"Tambah Karyawan"**
4. Isi semua data karyawan
5. Di bagian **"Foto"**, klik **"Choose File"**
6. Pilih foto (JPG/JPEG/PNG, max 2MB)
7. **Preview akan muncul otomatis**
8. Klik **"Simpan"**
9. Foto akan tersimpan dan tampil di tabel

### **Edit Foto Karyawan:**
1. Di halaman **"Karyawan"**, klik tombol **Edit** (ikon pensil)
2. Foto lama akan ditampilkan (jika ada)
3. Untuk mengganti foto:
   - Klik **"Choose File"**
   - Pilih foto baru
   - **Preview akan langsung berubah**
4. Klik **"Update"**
5. Foto lama akan dihapus dan diganti dengan foto baru

### **Lihat Foto di Daftar:**
1. Buka menu **"Karyawan"**
2. Foto akan tampil di kolom pertama tabel
3. Klik foto untuk melihat detail karyawan

---

## 💾 Lokasi Penyimpanan Foto

- **Folder**: `storage/app/public/karyawan/`
- **Akses Public**: `public/storage/karyawan/` (via symbolic link)
- **URL**: `http://localhost:8000/storage/karyawan/nama_file.jpg`

---

## 🔧 Fitur Teknis

### **1. Avatar Otomatis**
Jika karyawan belum upload foto, sistem akan generate avatar otomatis menggunakan:
- **Service**: UI Avatars API (https://ui-avatars.com)
- **Parameter**: 
  - `name`: Nama karyawan
  - `background`: Random color
  - `size`: 50px

**Contoh URL:**
```
https://ui-avatars.com/api/?name=John+Doe&background=random&size=50
```

### **2. Preview Real-time**
Menggunakan JavaScript FileReader API untuk membaca file dan menampilkan preview sebelum upload.

**Kode JavaScript:**
```javascript
document.getElementById('foto-input').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
```

### **3. Validasi Upload**
- **Format**: JPG, JPEG, PNG
- **Ukuran**: Maksimal 2MB
- **Validasi di Controller**: 
  ```php
  'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
  ```

### **4. Penghapusan Foto Lama**
Saat update foto, foto lama akan dihapus otomatis:
```php
if ($request->hasFile('foto')) {
    if ($karyawan->foto) {
        Storage::disk('public')->delete($karyawan->foto);
    }
    $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
}
```

---

## 🎯 Perubahan File

### **File yang Diupdate:**

1. **`resources/views/karyawan/index.blade.php`**
   - ✅ Tambah kolom foto di tabel
   - ✅ Tampilkan thumbnail foto atau avatar
   - ✅ Styling untuk foto 50x50px

2. **`resources/views/karyawan/create.blade.php`**
   - ✅ Tambah preview container
   - ✅ Tambah JavaScript untuk preview real-time
   - ✅ Tambah informasi format dan ukuran

3. **`resources/views/karyawan/edit.blade.php`**
   - ✅ Tampilkan foto lama
   - ✅ Preview foto baru saat dipilih
   - ✅ JavaScript untuk update preview

4. **`resources/views/karyawan/show.blade.php`**
   - ✅ Sudah ada dari awal (foto besar di profil)

---

## 📱 Responsive Design

Foto akan tetap tampil baik di:
- ✅ Desktop (full size)
- ✅ Tablet (medium size)
- ✅ Mobile (small size, tabel scroll horizontal)

---

## 🐛 Troubleshooting

### **Foto tidak muncul setelah upload**
**Solusi:**
```bash
php artisan storage:link
chmod -R 775 storage/app/public/karyawan
```

### **Error saat upload foto**
**Penyebab:**
- File terlalu besar (> 2MB)
- Format tidak didukung (bukan JPG/JPEG/PNG)
- Permission folder storage salah

**Solusi:**
```bash
# Cek permission
ls -la storage/app/public/karyawan

# Set permission jika perlu
chmod -R 775 storage/app/public/karyawan
```

### **Preview tidak muncul**
**Penyebab:**
- JavaScript error
- Browser tidak support FileReader API

**Solusi:**
- Cek console browser (F12)
- Gunakan browser modern (Chrome, Firefox, Edge)

---

## ✨ Fitur Tambahan yang Bisa Dikembangkan

1. **Crop Foto** - Crop foto sebelum upload
2. **Compress Foto** - Kompres foto otomatis
3. **Multiple Upload** - Upload beberapa foto sekaligus
4. **Drag & Drop** - Upload dengan drag and drop
5. **Webcam Capture** - Ambil foto langsung dari webcam

---

**Status: ✅ FITUR FOTO LENGKAP DAN BERFUNGSI!**

Sekarang foto karyawan akan tampil di semua halaman CRUD dengan preview real-time! 🎉
