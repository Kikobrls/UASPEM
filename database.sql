-- =====================================================
-- SQL Script untuk Membuat Database Sistem Penggajian
-- =====================================================
-- Jalankan script ini jika Anda ingin membuat database secara manual
-- atau jika perintah artisan migrate tidak berfungsi

-- 1. Buat Database
CREATE DATABASE IF NOT EXISTS kaw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kaw;

-- 2. Hapus tabel jika sudah ada (opsional, hati-hati!)
-- DROP TABLE IF EXISTS potongan;
-- DROP TABLE IF EXISTS bonus;
-- DROP TABLE IF EXISTS gaji;
-- DROP TABLE IF EXISTS karyawan;
-- DROP TABLE IF EXISTS jabatan;
-- DROP TABLE IF EXISTS users;

-- 3. Buat Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'karyawan') NOT NULL DEFAULT 'karyawan',
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Buat Tabel Jabatan
CREATE TABLE IF NOT EXISTS jabatan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_jabatan VARCHAR(255) NOT NULL,
    gaji_pokok DECIMAL(15, 2) NOT NULL,
    deskripsi TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Buat Tabel Karyawan
CREATE TABLE IF NOT EXISTS karyawan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    jabatan_id BIGINT UNSIGNED NOT NULL,
    nip VARCHAR(255) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(255) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT NULL,
    no_telepon VARCHAR(20) NULL,
    tanggal_lahir DATE NULL,
    tanggal_masuk DATE NOT NULL,
    foto VARCHAR(255) NULL,
    status ENUM('aktif', 'nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (jabatan_id) REFERENCES jabatan(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Buat Tabel Gaji
CREATE TABLE IF NOT EXISTS gaji (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    karyawan_id BIGINT UNSIGNED NOT NULL,
    bulan INT NOT NULL,
    tahun INT NOT NULL,
    gaji_pokok DECIMAL(15, 2) NOT NULL,
    total_bonus DECIMAL(15, 2) NOT NULL DEFAULT 0,
    total_potongan DECIMAL(15, 2) NOT NULL DEFAULT 0,
    gaji_bersih DECIMAL(15, 2) NOT NULL,
    status ENUM('draft', 'disetujui', 'dibayar') NOT NULL DEFAULT 'draft',
    disetujui_oleh BIGINT UNSIGNED NULL,
    tanggal_disetujui TIMESTAMP NULL,
    tanggal_dibayar TIMESTAMP NULL,
    catatan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_gaji (karyawan_id, bulan, tahun),
    FOREIGN KEY (karyawan_id) REFERENCES karyawan(id) ON DELETE CASCADE,
    FOREIGN KEY (disetujui_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Buat Tabel Bonus
CREATE TABLE IF NOT EXISTS bonus (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gaji_id BIGINT UNSIGNED NOT NULL,
    nama_bonus VARCHAR(255) NOT NULL,
    jumlah DECIMAL(15, 2) NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (gaji_id) REFERENCES gaji(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Buat Tabel Potongan
CREATE TABLE IF NOT EXISTS potongan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    gaji_id BIGINT UNSIGNED NOT NULL,
    nama_potongan VARCHAR(255) NOT NULL,
    jumlah DECIMAL(15, 2) NOT NULL,
    keterangan TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (gaji_id) REFERENCES gaji(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- Data Awal (Seeder)
-- =====================================================

-- Insert Jabatan
INSERT INTO jabatan (nama_jabatan, gaji_pokok, deskripsi, created_at, updated_at) VALUES
('Administrator', 10000000, 'Jabatan administrator sistem', NOW(), NOW()),
('Manager', 8000000, 'Jabatan manager', NOW(), NOW()),
('Staff', 5000000, 'Jabatan staff', NOW(), NOW()),
('Operator', 4000000, 'Jabatan operator', NOW(), NOW());

-- Insert Users (Password: admin123, manager123, karyawan123)
-- Password hash untuk 'admin123': $2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
INSERT INTO users (name, email, password, role, created_at, updated_at) VALUES
('Admin', 'admin@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW()),
('Manager', 'manager@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', NOW(), NOW()),
('Karyawan 1', 'karyawan1@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan', NOW(), NOW()),
('Karyawan 2', 'karyawan2@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan', NOW(), NOW()),
('Karyawan 3', 'karyawan3@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan', NOW(), NOW()),
('Karyawan 4', 'karyawan4@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan', NOW(), NOW()),
('Karyawan 5', 'karyawan5@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'karyawan', NOW(), NOW());

-- Insert Karyawan
INSERT INTO karyawan (user_id, jabatan_id, nip, nama_lengkap, jenis_kelamin, alamat, no_telepon, tanggal_lahir, tanggal_masuk, status, created_at, updated_at) VALUES
(1, 1, 'NIP001', 'Administrator Sistem', 'L', 'Jl. Admin No. 1', '081234567890', '1990-01-01', '2020-01-01', 'aktif', NOW(), NOW()),
(2, 2, 'NIP002', 'Manager HRD', 'L', 'Jl. Manager No. 2', '081234567891', '1992-02-02', '2020-02-01', 'aktif', NOW(), NOW()),
(3, 4, 'NIP003', 'Karyawan Nomor 1', 'L', 'Jl. Karyawan No. 1', '0812345678901', '1991-01-01', '2021-01-01', 'aktif', NOW(), NOW()),
(4, 3, 'NIP004', 'Karyawan Nomor 2', 'P', 'Jl. Karyawan No. 2', '0812345678902', '1992-02-02', '2021-02-01', 'aktif', NOW(), NOW()),
(5, 4, 'NIP005', 'Karyawan Nomor 3', 'L', 'Jl. Karyawan No. 3', '0812345678903', '1993-03-03', '2021-03-01', 'aktif', NOW(), NOW()),
(6, 3, 'NIP006', 'Karyawan Nomor 4', 'P', 'Jl. Karyawan No. 4', '0812345678904', '1994-04-04', '2021-04-01', 'aktif', NOW(), NOW()),
(7, 4, 'NIP007', 'Karyawan Nomor 5', 'L', 'Jl. Karyawan No. 5', '0812345678905', '1995-05-05', '2021-05-01', 'aktif', NOW(), NOW());

-- =====================================================
-- Selesai!
-- =====================================================
-- Database dan data awal sudah dibuat.
-- Anda bisa login dengan:
-- Admin: admin@gmail.com / admin123
-- Manager: manager@gmail.com / manager123
-- Karyawan: karyawan1@gmail.com / karyawan123
