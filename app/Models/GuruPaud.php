<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruPaud extends Model
{
    use HasFactory;

    protected $table = 'guru_paud';

    protected $fillable = [
        'lembaga_id',
        'nama',
        'nuptk',
        'pendidikan_terakhir',
        'status_guru',
        'jenis_kelamin',
        'sertifikasi',
        'pelatihan_terakhir',
        'tahun_masuk',
    ];

    public function lembaga()
    {
        return $this->belongsTo(LembagaPaud::class, 'lembaga_id');
    }
}
