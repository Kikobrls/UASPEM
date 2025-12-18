<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Jabatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Tampilkan daftar karyawan
     */
    public function index()
    {
        $karyawan = Karyawan::with(['user', 'jabatan'])->get();
        return view('karyawan.index', compact('karyawan'));
    }

    /**
     * Tampilkan form tambah karyawan
     */
    public function create()
    {
        $jabatan = Jabatan::all();
        return view('karyawan.create', compact('jabatan'));
    }

    /**
     * Simpan karyawan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,manager,karyawan',
            'jabatan_id' => 'required|exists:jabatan,id',
            'nip' => 'required|unique:karyawan,nip',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Buat user
        $user = User::create([
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Upload foto jika ada
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('karyawan', 'public');
        }

        // Buat karyawan
        Karyawan::create([
            'user_id' => $user->id,
            'jabatan_id' => $validated['jabatan_id'],
            'nip' => $validated['nip'],
            'nama_lengkap' => $validated['nama_lengkap'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'alamat' => $validated['alamat'],
            'no_telepon' => $validated['no_telepon'],
            'tanggal_lahir' => $validated['tanggal_lahir'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'foto' => $fotoPath,
            'status' => 'aktif',
        ]);

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail karyawan
     */
    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['user', 'jabatan', 'gaji']);
        return view('karyawan.show', compact('karyawan'));
    }

    /**
     * Tampilkan form edit karyawan
     */
    public function edit(Karyawan $karyawan)
    {
        $jabatan = Jabatan::all();
        return view('karyawan.edit', compact('karyawan', 'jabatan'));
    }

    /**
     * Update karyawan
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $karyawan->user_id,
            'password' => 'nullable|min:6',
            'role' => 'required|in:admin,manager,karyawan',
            'jabatan_id' => 'required|exists:jabatan,id',
            'nip' => 'required|unique:karyawan,nip,' . $karyawan->id,
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|max:20',
            'tanggal_lahir' => 'nullable|date',
            'tanggal_masuk' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Update user
        $userData = [
            'name' => $validated['nama_lengkap'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $karyawan->user->update($userData);

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($karyawan->foto) {
                Storage::disk('public')->delete($karyawan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('karyawan', 'public');
        }

        // Update karyawan
        $karyawan->update($validated);

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil diupdate.');
    }

    /**
     * Hapus karyawan
     */
    public function destroy(Karyawan $karyawan)
    {
        // Hapus foto jika ada
        if ($karyawan->foto) {
            Storage::disk('public')->delete($karyawan->foto);
        }

        // Hapus user (akan cascade delete karyawan)
        $karyawan->user->delete();

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan berhasil dihapus.');
    }
}
