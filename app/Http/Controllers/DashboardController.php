<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Gaji;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isManager()) {
            return $this->managerDashboard();
        } else {
            return $this->karyawanDashboard();
        }
    }

    /**
     * Dashboard untuk admin
     */
    private function adminDashboard()
    {
        $totalKaryawan = Karyawan::where('status', 'aktif')->count();
        $totalGajiDraft = Gaji::where('status', 'draft')->count();
        $totalGajiMenunggu = Gaji::where('status', 'disetujui')->count();
        $totalGajiDibayar = Gaji::where('status', 'dibayar')->count();
        
        $gajiTerbaru = Gaji::with(['karyawan.jabatan'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact(
            'totalKaryawan',
            'totalGajiDraft',
            'totalGajiMenunggu',
            'totalGajiDibayar',
            'gajiTerbaru'
        ));
    }

    /**
     * Dashboard untuk manager
     */
    private function managerDashboard()
    {
        $totalKaryawan = Karyawan::where('status', 'aktif')->count();
        $gajiMenungguApproval = Gaji::where('status', 'draft')->count();
        $gajiDisetujui = Gaji::where('status', 'disetujui')->count();
        
        $gajiMenunggu = Gaji::with(['karyawan.jabatan'])
            ->where('status', 'draft')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.manager', compact(
            'totalKaryawan',
            'gajiMenungguApproval',
            'gajiDisetujui',
            'gajiMenunggu'
        ));
    }

    /**
     * Dashboard untuk karyawan
     */
    private function karyawanDashboard()
    {
        $karyawan = auth()->user()->karyawan;
        
        if (!$karyawan) {
            abort(403, 'Data karyawan tidak ditemukan.');
        }

        $gajiTerbaru = Gaji::with(['bonus', 'potongan'])
            ->where('karyawan_id', $karyawan->id)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        $totalGajiDiterima = Gaji::where('karyawan_id', $karyawan->id)
            ->where('status', 'dibayar')
            ->sum('gaji_bersih');

        return view('dashboard.karyawan', compact(
            'karyawan',
            'gajiTerbaru',
            'totalGajiDiterima'
        ));
    }
}
