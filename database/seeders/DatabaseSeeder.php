<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed database dengan data awal
     */
    public function run(): void
    {
        // Buat jabatan
        $jabatanAdmin = Jabatan::create([
            'nama_jabatan' => 'Administrator',
            'gaji_pokok' => 10000000,
            'deskripsi' => 'Jabatan administrator sistem'
        ]);

        $jabatanManager = Jabatan::create([
            'nama_jabatan' => 'Manager',
            'gaji_pokok' => 8000000,
            'deskripsi' => 'Jabatan manager'
        ]);

        $jabatanStaff = Jabatan::create([
            'nama_jabatan' => 'Staff',
            'gaji_pokok' => 5000000,
            'deskripsi' => 'Jabatan staff'
        ]);

        $jabatanOperator = Jabatan::create([
            'nama_jabatan' => 'Operator',
            'gaji_pokok' => 4000000,
            'deskripsi' => 'Jabatan operator'
        ]);

        // Buat user admin
        $userAdmin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        Karyawan::create([
            'user_id' => $userAdmin->id,
            'jabatan_id' => $jabatanAdmin->id,
            'nip' => 'NIP001',
            'nama_lengkap' => 'Administrator Sistem',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Admin No. 1',
            'no_telepon' => '081234567890',
            'tanggal_lahir' => '1990-01-01',
            'tanggal_masuk' => '2020-01-01',
            'status' => 'aktif',
        ]);

        // Buat user manager
        $userManager = User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('manager123'),
            'role' => 'manager',
        ]);

        Karyawan::create([
            'user_id' => $userManager->id,
            'jabatan_id' => $jabatanManager->id,
            'nip' => 'NIP002',
            'nama_lengkap' => 'Manager HRD',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Manager No. 2',
            'no_telepon' => '081234567891',
            'tanggal_lahir' => '1992-02-02',
            'tanggal_masuk' => '2020-02-01',
            'status' => 'aktif',
        ]);

        // Buat beberapa user karyawan
        for ($i = 1; $i <= 5; $i++) {
            $userKaryawan = User::create([
                'name' => "Karyawan $i",
                'email' => "karyawan$i@gmail.com",
                'password' => Hash::make('karyawan123'),
                'role' => 'karyawan',
            ]);

            Karyawan::create([
                'user_id' => $userKaryawan->id,
                'jabatan_id' => $i % 2 == 0 ? $jabatanStaff->id : $jabatanOperator->id,
                'nip' => 'NIP' . str_pad(2 + $i, 3, '0', STR_PAD_LEFT),
                'nama_lengkap' => "Karyawan Nomor $i",
                'jenis_kelamin' => $i % 2 == 0 ? 'P' : 'L',
                'alamat' => "Jl. Karyawan No. $i",
                'no_telepon' => '08123456789' . $i,
                'tanggal_lahir' => '199' . $i . '-0' . $i . '-0' . $i,
                'tanggal_masuk' => '2021-0' . $i . '-01',
                'status' => 'aktif',
            ]);
        }
    }
}
