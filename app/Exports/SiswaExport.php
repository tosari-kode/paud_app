<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Siswa::with('lembaga:id,nama_lembaga')
            ->get()
            ->map(function ($s) {
                return [
                    'ID' => $s->id,
                    'Nama' => $s->nama,
                    'Jenis Kelamin' => $s->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan',
                    'Tanggal Lahir' => $s->tanggal_lahir,
                    'Tahun Masuk' => $s->tahun_masuk,
                    'Status' => ucfirst($s->status),
                    'Lembaga' => $s->lembaga->nama_lembaga ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Jenis Kelamin',
            'Tanggal Lahir',
            'Tahun Masuk',
            'Status',
            'Lembaga',
        ];
    }
}
