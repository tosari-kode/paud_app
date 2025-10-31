<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'lembaga_id',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tahun_masuk',
        'status',
    ];

    /**
     * Relasi ke model LembagaPaud
     * Setiap siswa dimiliki oleh satu lembaga PAUD
     */
    public function lembaga()
    {
        return $this->belongsTo(LembagaPaud::class, 'lembaga_id');
    }
}
