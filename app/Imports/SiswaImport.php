<?php

namespace App\Imports;

use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date; // <-- Tambahkan ini


class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // dd($row);
        try {
            // Konversi tanggal dari serial number Excel ke format tanggal
        $tanggal = null;

        if (!empty($row['tanggal_lahir'])) {
            // Jika berupa angka (serial Excel), ubah ke format tanggal
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggal = Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                // Jika sudah berupa string tanggal
                $tanggal = date('Y-m-d', strtotime($row['tanggal_lahir']));
            }
        }
            return new Siswa([
                'lembaga_id' => $row['lembaga_id'],
                'nama' => $row['nama'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'tanggal_lahir' => $tanggal,
                'tahun_masuk' => $row['tahun_masuk'],
                'status' => $row['status'],
            ]);
        } catch (\Throwable $e) {
            Log::error('Import gagal: ' . $e->getMessage(), ['row' => $row]);
            return null;
        }
    }
}
