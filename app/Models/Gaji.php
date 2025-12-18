<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'gaji';

    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'gaji_pokok',
        'total_bonus',
        'total_potongan',
        'gaji_bersih',
        'status',
        'disetujui_oleh',
        'tanggal_disetujui',
        'tanggal_dibayar',
        'catatan',
    ];

    protected $casts = [
        'gaji_pokok' => 'decimal:2',
        'total_bonus' => 'decimal:2',
        'total_potongan' => 'decimal:2',
        'gaji_bersih' => 'decimal:2',
        'tanggal_disetujui' => 'datetime',
        'tanggal_dibayar' => 'datetime',
    ];

    /**
     * Relasi ke karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    /**
     * Relasi ke user yang menyetujui
     */
    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Relasi ke bonus
     */
    public function bonus()
    {
        return $this->hasMany(Bonus::class);
    }

    /**
     * Relasi ke potongan
     */
    public function potongan()
    {
        return $this->hasMany(Potongan::class);
    }

    /**
     * Hitung ulang total bonus
     */
    public function hitungTotalBonus()
    {
        $this->total_bonus = $this->bonus()->sum('jumlah');
        return $this->total_bonus;
    }

    /**
     * Hitung ulang total potongan
     */
    public function hitungTotalPotongan()
    {
        $this->total_potongan = $this->potongan()->sum('jumlah');
        return $this->total_potongan;
    }

    /**
     * Hitung gaji bersih
     */
    public function hitungGajiBersih()
    {
        $this->gaji_bersih = $this->gaji_pokok + $this->total_bonus - $this->total_potongan;
        return $this->gaji_bersih;
    }
}
