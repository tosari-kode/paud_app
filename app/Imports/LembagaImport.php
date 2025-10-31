<?php

namespace App\Imports;

use App\Models\LembagaPaud;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LembagaImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new LembagaPaud([
            'nama_lembaga' => $row['nama_lembaga'],
            'npsn' => $row['npsn'],
            'jenis_lembaga' => $row['jenis_lembaga'],
            'status_izin' => $row['status_izin'],
            'akreditasi' => $row['akreditasi'],
            'alamat' => $row['alamat'],
            'desa_id' => $row['desa_id'],
            'kepala_lembaga' => $row['kepala_lembaga'],
            'no_hp' => $row['no_hp'],
            'email' => $row['email'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
        ]);
    }
}
