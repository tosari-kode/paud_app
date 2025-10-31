<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan';

    protected $fillable = [
        'lembaga_id',
        'jenis_laporan',
        'periode',
        'file_laporan',
        'status_verifikasi',
        'catatan_verifikasi',
    ];

    /**
     * Relasi ke tabel lembaga_paud
     */
    public function lembaga()
    {
        return $this->belongsTo(LembagaPaud::class, 'lembaga_id');
    }

    /**
     * Akses URL file laporan (misal untuk menampilkan di view)
     */
    public function getFileUrlAttribute()
    {
        return $this->file_laporan
            ? asset('storage/' . $this->file_laporan)
            : null;
    }
}
