<?php

namespace App\Http\Controllers;

use App\Models\Gaji;
use App\Models\Karyawan;
use App\Models\Bonus;
use App\Models\Potongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    /**
     * Tampilkan daftar gaji
     */
    public function index(Request $request)
    {
        $query = Gaji::with(['karyawan.jabatan', 'disetujuiOleh']);

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan bulan
        if ($request->has('bulan') && $request->bulan != '') {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun', $request->tahun);
        }

        $gaji = $query->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(20);

        return view('gaji.index', compact('gaji'));
    }

    /**
     * Tampilkan form tambah gaji
     */
    public function create()
    {
        $karyawan = Karyawan::with('jabatan')->where('status', 'aktif')->get();
        return view('gaji.create', compact('karyawan'));
    }

    /**
     * Simpan gaji baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'catatan' => 'nullable|string',
            'bonus' => 'nullable|array',
            'bonus.*.nama' => 'exclude_if:bonus.*.nama,null|string',
            'bonus.*.jumlah' => 'exclude_if:bonus.*.jumlah,null|numeric|min:0',
            'bonus.*.keterangan' => 'nullable|string',
            'potongan' => 'nullable|array',
            'potongan.*.nama' => 'exclude_if:potongan.*.nama,null|string',
            'potongan.*.jumlah' => 'exclude_if:potongan.*.jumlah,null|numeric|min:0',
            'potongan.*.keterangan' => 'nullable|string',
        ]);

        // Cek duplikasi
        $exists = Gaji::where('karyawan_id', $validated['karyawan_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Gaji untuk karyawan ini pada bulan dan tahun yang sama sudah ada.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $karyawan = Karyawan::findOrFail($validated['karyawan_id']);
            
            // Hitung total bonus dan potongan
            $totalBonus = 0;
            if (isset($validated['bonus'])) {
                foreach ($validated['bonus'] as $bonusData) {
                    if (!empty($bonusData['nama']) && !empty($bonusData['jumlah'])) {
                        $totalBonus += $bonusData['jumlah'];
                    }
                }
            }
            
            $totalPotongan = 0;
            if (isset($validated['potongan'])) {
                foreach ($validated['potongan'] as $potonganData) {
                    if (!empty($potonganData['nama']) && !empty($potonganData['jumlah'])) {
                        $totalPotongan += $potonganData['jumlah'];
                    }
                }
            }
            
            // Hitung gaji bersih
            $gajiPokok = $karyawan->jabatan->gaji_pokok;
            $gajiBersih = $gajiPokok + $totalBonus - $totalPotongan;
            
            // Buat gaji
            $gaji = Gaji::create([
                'karyawan_id' => $validated['karyawan_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'gaji_pokok' => $gajiPokok,
                'total_bonus' => $totalBonus,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gajiBersih,
                'catatan' => $validated['catatan'],
                'status' => 'draft',
            ]);

            // Tambah bonus
            if (isset($validated['bonus'])) {
                foreach ($validated['bonus'] as $bonusData) {
                    if (!empty($bonusData['nama']) && !empty($bonusData['jumlah'])) {
                        Bonus::create([
                            'gaji_id' => $gaji->id,
                            'nama_bonus' => $bonusData['nama'],
                            'jumlah' => $bonusData['jumlah'],
                            'keterangan' => $bonusData['keterangan'] ?? null,
                        ]);
                    }
                }
            }

            // Tambah potongan
            if (isset($validated['potongan'])) {
                foreach ($validated['potongan'] as $potonganData) {
                    if (!empty($potonganData['nama']) && !empty($potonganData['jumlah'])) {
                        Potongan::create([
                            'gaji_id' => $gaji->id,
                            'nama_potongan' => $potonganData['nama'],
                            'jumlah' => $potonganData['jumlah'],
                            'keterangan' => $potonganData['keterangan'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('gaji.index')
                ->with('success', 'Gaji berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail gaji (slip gaji)
     */
    public function show(Gaji $gaji)
    {
        $gaji->load(['karyawan.jabatan', 'bonus', 'potongan', 'disetujuiOleh']);
        
        // Cek akses untuk karyawan
        if (auth()->user()->isKaryawan()) {
            if ($gaji->karyawan->user_id != auth()->id()) {
                abort(403, 'Anda tidak memiliki akses ke slip gaji ini.');
            }
        }

        return view('gaji.show', compact('gaji'));
    }

    /**
     * Tampilkan form edit gaji
     */
    public function edit(Gaji $gaji)
    {
        // Hanya bisa edit jika status masih draft
        if ($gaji->status != 'draft') {
            return back()->with('error', 'Gaji yang sudah disetujui tidak bisa diedit.');
        }

        $karyawan = Karyawan::with('jabatan')->where('status', 'aktif')->get();
        $gaji->load(['bonus', 'potongan']);
        
        return view('gaji.edit', compact('gaji', 'karyawan'));
    }

    /**
     * Update gaji
     */
    public function update(Request $request, Gaji $gaji)
    {
        // Hanya bisa edit jika status masih draft
        if ($gaji->status != 'draft') {
            return back()->with('error', 'Gaji yang sudah disetujui tidak bisa diedit.');
        }

        $validated = $request->validate([
            'karyawan_id' => 'required|exists:karyawan,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020',
            'catatan' => 'nullable|string',
            'bonus' => 'nullable|array',
            'bonus.*.nama' => 'exclude_if:bonus.*.nama,null|string',
            'bonus.*.jumlah' => 'exclude_if:bonus.*.jumlah,null|numeric|min:0',
            'bonus.*.keterangan' => 'nullable|string',
            'potongan' => 'nullable|array',
            'potongan.*.nama' => 'exclude_if:potongan.*.nama,null|string',
            'potongan.*.jumlah' => 'exclude_if:potongan.*.jumlah,null|numeric|min:0',
            'potongan.*.keterangan' => 'nullable|string',
        ]);

        // Cek duplikasi (kecuali untuk gaji yang sedang diedit)
        $exists = Gaji::where('karyawan_id', $validated['karyawan_id'])
            ->where('bulan', $validated['bulan'])
            ->where('tahun', $validated['tahun'])
            ->where('id', '!=', $gaji->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Gaji untuk karyawan ini pada bulan dan tahun yang sama sudah ada.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $karyawan = Karyawan::findOrFail($validated['karyawan_id']);
            
            // Hapus bonus dan potongan lama
            $gaji->bonus()->delete();
            $gaji->potongan()->delete();

            // Update gaji
            $gaji->update([
                'karyawan_id' => $validated['karyawan_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'gaji_pokok' => $karyawan->jabatan->gaji_pokok,
                'catatan' => $validated['catatan'],
            ]);

            // Tambah bonus baru
            $totalBonus = 0;
            if (isset($validated['bonus'])) {
                foreach ($validated['bonus'] as $bonusData) {
                    if (!empty($bonusData['nama']) && !empty($bonusData['jumlah'])) {
                        Bonus::create([
                            'gaji_id' => $gaji->id,
                            'nama_bonus' => $bonusData['nama'],
                            'jumlah' => $bonusData['jumlah'],
                            'keterangan' => $bonusData['keterangan'] ?? null,
                        ]);
                        $totalBonus += $bonusData['jumlah'];
                    }
                }
            }

            // Tambah potongan baru
            $totalPotongan = 0;
            if (isset($validated['potongan'])) {
                foreach ($validated['potongan'] as $potonganData) {
                    if (!empty($potonganData['nama']) && !empty($potonganData['jumlah'])) {
                        Potongan::create([
                            'gaji_id' => $gaji->id,
                            'nama_potongan' => $potonganData['nama'],
                            'jumlah' => $potonganData['jumlah'],
                            'keterangan' => $potonganData['keterangan'] ?? null,
                        ]);
                        $totalPotongan += $potonganData['jumlah'];
                    }
                }
            }

            // Update total
            $gaji->update([
                'total_bonus' => $totalBonus,
                'total_potongan' => $totalPotongan,
                'gaji_bersih' => $gaji->gaji_pokok + $totalBonus - $totalPotongan,
            ]);

            DB::commit();

            return redirect()->route('gaji.index')
                ->with('success', 'Gaji berhasil diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Setujui gaji (untuk manager/admin)
     */
    public function approve(Gaji $gaji)
    {
        if ($gaji->status != 'draft') {
            return back()->with('error', 'Gaji ini sudah disetujui atau dibayar.');
        }

        $gaji->update([
            'status' => 'disetujui',
            'disetujui_oleh' => auth()->id(),
            'tanggal_disetujui' => now(),
        ]);

        return back()->with('success', 'Gaji berhasil disetujui.');
    }

    /**
     * Tandai gaji sebagai dibayar (untuk admin)
     */
    public function pay(Gaji $gaji)
    {
        if ($gaji->status != 'disetujui') {
            return back()->with('error', 'Gaji harus disetujui terlebih dahulu.');
        }

        $gaji->update([
            'status' => 'dibayar',
            'tanggal_dibayar' => now(),
        ]);

        return back()->with('success', 'Gaji berhasil ditandai sebagai dibayar.');
    }

    /**
     * Hapus gaji
     */
    public function destroy(Gaji $gaji)
    {
        // Hanya bisa hapus jika status masih draft
        if ($gaji->status != 'draft') {
            return back()->with('error', 'Gaji yang sudah disetujui tidak bisa dihapus.');
        }

        $gaji->delete();

        return redirect()->route('gaji.index')
            ->with('success', 'Gaji berhasil dihapus.');
    }

    /**
     * Slip gaji karyawan (untuk karyawan melihat slip gaji sendiri)
     */
    public function mySlip()
    {
        $karyawan = auth()->user()->karyawan;
        
        if (!$karyawan) {
            abort(403, 'Data karyawan tidak ditemukan.');
        }

        $gaji = Gaji::with(['bonus', 'potongan'])
            ->where('karyawan_id', $karyawan->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(12);

        return view('gaji.my-slip', compact('gaji', 'karyawan'));
    }
}
