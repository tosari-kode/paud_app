<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LembagaPaud;
use App\Models\Desa;

class LembagaPaudSeeder extends Seeder
{
    public function run(): void
    {
        $labuha = Desa::where('nama_desa', 'Desa Labuha')->first();
        $amasing = Desa::where('nama_desa', 'Desa Amasing')->first();

        $data = [
            [
                'nama_lembaga' => 'PAUD Cemerlang Labuha',
                'npsn' => 'NPSN001',
                'jenis_lembaga' => 'TK',
                'status_izin' => 'izin',
                'akreditasi' => 'A',
                'alamat' => 'Jl. Raya Labuha No.1',
                'desa_id' => $labuha->id,
                'kepala_lembaga' => 'Ibu Rahmawati',
                'no_hp' => '081234567890',
                'email' => 'paudlabuha@example.com',
                'latitude' => '-0.633',
                'longitude' => '127.502',
                'foto' => 'default.jpg',
                'status' => 1,
            ],
            [
                'nama_lembaga' => 'PAUD Harapan Amasing',
                'npsn' => 'NPSN002',
                'jenis_lembaga' => 'KB',
                'status_izin' => 'proses',
                'akreditasi' => 'B',
                'alamat' => 'Jl. Amasing Tengah No.12',
                'desa_id' => $amasing->id,
                'kepala_lembaga' => 'Ibu Siti Nur',
                'no_hp' => '081298765432',
                'email' => 'paudamasing@example.com',
                'latitude' => '-0.640',
                'longitude' => '127.510',
                'foto' => 'default.jpg',
                'status' => 1,
            ],
        ];

        foreach ($data as $item) {
            LembagaPaud::create($item);
        }
    }
}
