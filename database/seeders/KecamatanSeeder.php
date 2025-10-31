<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kecamatan;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kecamatan' => 'Bacan', 'kode' => 'KC001'],
            ['nama_kecamatan' => 'Bacan Timur', 'kode' => 'KC002'],
            ['nama_kecamatan' => 'Bacan Barat', 'kode' => 'KC003'],
        ];

        foreach ($data as $item) {
            Kecamatan::create($item);
        }
    }
}
