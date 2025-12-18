<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    /**
     * Tampilkan daftar jabatan
     */
    public function index()
    {
        $jabatan = Jabatan::withCount('karyawan')->get();
        return view('jabatan.index', compact('jabatan'));
    }

    /**
     * Tampilkan form tambah jabatan
     */
    public function create()
    {
        return view('jabatan.create');
    }

    /**
     * Simpan jabatan baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        Jabatan::create($validated);

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit jabatan
     */
    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    /**
     * Update jabatan
     */
    public function update(Request $request, Jabatan $jabatan)
    {
        $validated = $request->validate([
            'nama_jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
        ]);

        $jabatan->update($validated);

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil diupdate.');
    }

    /**
     * Hapus jabatan
     */
    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->karyawan()->count() > 0) {
            return back()->with('error', 'Jabatan tidak bisa dihapus karena masih ada karyawan yang menggunakan jabatan ini.');
        }

        $jabatan->delete();

        return redirect()->route('jabatan.index')
            ->with('success', 'Jabatan berhasil dihapus.');
    }
}
