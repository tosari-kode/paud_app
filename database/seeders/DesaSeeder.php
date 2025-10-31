<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Desa;
use App\Models\Kecamatan;

class DesaSeeder extends Seeder
{
    public function run(): void
    {
        $bacan = Kecamatan::where('nama_kecamatan', 'Bacan')->first();
        $bacanTimur = Kecamatan::where('nama_kecamatan', 'Bacan Timur')->first();
        $bacanBarat = Kecamatan::where('nama_kecamatan', 'Bacan Barat')->first();

        $data = [
            ['nama_desa' => 'Desa Labuha', 'kode' => 'DS001', 'kecamatan_id' => $bacan->id],
            ['nama_desa' => 'Desa Amasing', 'kode' => 'DS002', 'kecamatan_id' => $bacanTimur->id],
            ['nama_desa' => 'Desa Wayamiga', 'kode' => 'DS003', 'kecamatan_id' => $bacanBarat->id],
        ];

        foreach ($data as $item) {
            Desa::create($item);
        }
    }
}
