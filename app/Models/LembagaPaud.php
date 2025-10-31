<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LembagaPaud extends Model
{
    use HasFactory;

    protected $table = 'lembaga_paud';
    protected $fillable = [
        'nama_lembaga', 'npsn', 'jenis_lembaga', 'status_izin',
        'akreditasi', 'alamat', 'desa_id', 'kepala_lembaga', 'no_hp',
        'email', 'latitude', 'longitude', 'foto', 'status'
    ];

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'lembaga_id');
    }

    public function guru()
    {
        return $this->hasMany(GuruPaud::class, 'lembaga_id');
    }
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'lembaga_id');
    }
}

