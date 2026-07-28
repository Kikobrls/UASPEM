# Pengujian White Box — Data Iuran, Petugas, dan Kelas
Sistem Keuangan TPQ (Admin)

File diagram: `whitebox_iuran_petugas_kelas.drawio` (9 halaman, buka di https://app.diagrams.net)

Setiap halaman berisi: **Flowchart** (kiri) + **Tabel Flowgraph** berisi flowgraph node bulat,
rumus Cyclomatic Complexity, dan jalur independen — mengikuti desain contoh "Data Akun".

Rumus yang dipakai (dua-duanya harus menghasilkan nilai sama):
- Rumus 1: `V(G) = E − N + 2`
- Rumus 2: `V(G) = P + 1` (P = jumlah node keputusan/predikat)

---

## A. DATA IURAN

### 1. Tambah Data Iuran
- Node (n) = 12, Edge (e) = 13 → **V(G) = 13 − 12 + 2 = 3** (P = 2 → 2 + 1 = 3)
- Path 1 = 1,2,3,4,5,7,8,10,11,12
  (input valid → simpan berhasil → data iuran bertambah)
- Path 2 = 1,2,3,4,5,6,4,5,7,8,10,11,12
  (input kosong → pesan error → input ulang → simpan berhasil)
- Path 3 = 1,2,3,4,5,7,8,9,11,12
  (input valid → query gagal → pesan gagal ditampilkan)

### 2. Edit Data Iuran
- Node (n) = 12, Edge (e) = 13 → **V(G) = 3** (P = 2 → 3)
- Path 1 = 1,2,3,4,5,7,8,10,11,12
  (data valid → update berhasil → data iuran berubah)
- Path 2 = 1,2,3,4,5,6,4,5,7,8,10,11,12
  (input kosong → pesan error → input ulang → update berhasil)
- Path 3 = 1,2,3,4,5,7,8,9,11,12
  (data valid → query gagal → pesan gagal ditampilkan)

### 3. Hapus Data Iuran
- Node (n) = 12, Edge (e) = 14 → **V(G) = 14 − 12 + 2 = 4** (P = 3 → 4)
- Path 1 = 1,2,3,4,5,7,8,10,11,12
  (konfirmasi ya → iuran tidak digunakan santri → hapus berhasil)
- Path 2 = 1,2,3,4,2,3,4,5,7,8,10,11,12
  (batal konfirmasi → klik hapus lagi → hapus berhasil)
- Path 3 = 1,2,3,4,5,6,11,12
  (iuran digunakan santri → data tidak dapat dihapus)
- Path 4 = 1,2,3,4,5,7,8,9,11,12
  (konfirmasi ya → query gagal → pesan gagal ditampilkan)

---

## B. DATA PETUGAS

### 4. Tambah Data Petugas
- Node (n) = 14, Edge (e) = 16 → **V(G) = 16 − 14 + 2 = 4** (P = 3 → 4)
- Path 1 = 1,2,3,4,5,7,9,10,11,13,14
  (input valid → username tersedia → simpan berhasil)
- Path 2 = 1,2,3,4,5,6,4,5,7,9,10,11,13,14
  (input kosong → pesan error → input ulang → simpan berhasil)
- Path 3 = 1,2,3,4,5,7,8,4,5,7,9,10,11,13,14
  (username sudah digunakan → pesan error → ganti username → simpan berhasil)
- Path 4 = 1,2,3,4,5,7,9,10,12,13,14
  (input valid → query gagal → pesan gagal ditampilkan)

### 5. Edit Data Petugas
- Node (n) = 16, Edge (e) = 19 → **V(G) = 19 − 16 + 2 = 5** (P = 4 → 5)
- Path 1 = 1,2,3,4,5,7,9,11,12,13,15,16
  (data valid → tanpa password baru → update berhasil)
- Path 2 = 1,2,3,4,5,6,4,5,7,9,11,12,13,15,16
  (input kosong → pesan error → input ulang → update berhasil)
- Path 3 = 1,2,3,4,5,7,8,4,5,7,9,11,12,13,15,16
  (username dipakai petugas lain → pesan error → ganti username → update berhasil)
- Path 4 = 1,2,3,4,5,7,9,10,11,12,13,15,16
  (password baru diisi → password di-hash → update berhasil)
- Path 5 = 1,2,3,4,5,7,9,11,12,14,15,16
  (data valid → query gagal → pesan gagal ditampilkan)

### 6. Hapus Data Petugas
- Node (n) = 14, Edge (e) = 17 → **V(G) = 17 − 14 + 2 = 5** (P = 4 → 5)
- Path 1 = 1,2,3,4,5,7,9,10,11,13,14
  (konfirmasi ya → bukan akun sendiri → tanpa data pembayaran → hapus berhasil)
- Path 2 = 1,2,3,4,2,3,4,5,7,9,10,11,13,14
  (batal konfirmasi → klik hapus lagi → hapus berhasil)
- Path 3 = 1,2,3,4,5,6,13,14
  (menghapus akun sendiri → ditolak sistem → pesan error)
- Path 4 = 1,2,3,4,5,7,8,13,14
  (petugas memiliki data pembayaran → status diubah menjadi tidak aktif)
- Path 5 = 1,2,3,4,5,7,9,10,12,13,14
  (konfirmasi ya → query gagal → pesan gagal ditampilkan)

---

## C. DATA KELAS

### 7. Tambah Data Kelas
- Node (n) = 12, Edge (e) = 13 → **V(G) = 3** (P = 2 → 3)
- Path 1 = 1,2,3,4,5,7,8,10,11,12
  (input valid → simpan berhasil → data kelas bertambah)
- Path 2 = 1,2,3,4,5,6,4,5,7,8,10,11,12
  (nama kelas kosong → pesan error → input ulang → simpan berhasil)
- Path 3 = 1,2,3,4,5,7,8,9,11,12
  (input valid → query gagal → pesan gagal ditampilkan)

### 8. Edit Data Kelas
- Node (n) = 12, Edge (e) = 13 → **V(G) = 3** (P = 2 → 3)
- Path 1 = 1,2,3,4,5,7,8,10,11,12
  (data valid → update berhasil → nama kelas berubah)
- Path 2 = 1,2,3,4,5,6,4,5,7,8,10,11,12
  (nama kelas kosong → pesan error → input ulang → update berhasil)
- Path 3 = 1,2,3,4,5,7,8,9,11,12
  (data valid → query gagal → pesan gagal ditampilkan)

### 9. Hapus Data Kelas
- Node (n) = 12, Edge (e) = 14 → **V(G) = 4** (P = 3 → 4)
- Path 1 = 1,2,3,4,5,7,8,10,11,12
  (konfirmasi ya → kelas tanpa santri → hapus berhasil)
- Path 2 = 1,2,3,4,2,3,4,5,7,8,10,11,12
  (batal konfirmasi → klik hapus lagi → hapus berhasil)
- Path 3 = 1,2,3,4,5,6,11,12
  (kelas memiliki data santri → data tidak dapat dihapus)
- Path 4 = 1,2,3,4,5,7,8,9,11,12
  (konfirmasi ya → query gagal → pesan gagal ditampilkan)

---

## Kesimpulan
Seluruh jalur independen pada 9 skenario di atas telah diuji dan sesuai dengan logika
program (`pages/iuran/index.php`, `pages/petugas/index.php`, `pages/kelas/index.php`).
Nilai Cyclomatic Complexity V(G) dari Rumus 1 dan Rumus 2 selalu sama, sehingga
flowgraph dinyatakan **konsisten** dan pengujian white box dinyatakan **berhasil**.
