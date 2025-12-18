<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Potongan extends Model
{
    use HasFactory;

    protected $table = 'potongan';

    protected $fillable = [
        'gaji_id',
        'nama_potongan',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
    ];

    /**
     * Relasi ke gaji
     */
    public function gaji()
    {
        return $this->belongsTo(Gaji::class);
    }
}
