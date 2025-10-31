<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Models\Siswa;
use App\Models\LembagaPaud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->input('search');
            $siswa = Siswa::with('lembaga')
                ->when($search, function ($query, $search) {
                    return $query->where('nama', 'like', "%{$search}%")
                                 ->orWhere('tahun_masuk', 'like', "%{$search}%");
                })
                ->paginate(10);

            $lembaga = LembagaPaud::all();

            return view('backend.siswa.index', compact('siswa', 'lembaga', 'search'));
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memuat data siswa: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'lembaga_id' => 'required|exists:lembaga_paud,id',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4|integer',
            'status' => 'required|in:aktif,lulus,pindah',
        ]);

        try {
            Siswa::create($request->all());
            return back()->with('success', 'Data siswa berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'lembaga_id' => 'required|exists:lembaga_paud,id',
            'nama' => 'required|string|max:100',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'tahun_masuk' => 'required|digits:4|integer',
            'status' => 'required|in:aktif,lulus,pindah',
        ]);

        try {
            $siswa->update($request->all());
            return back()->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage());
        }
    }

    public function destroy(Siswa $siswa)
    {
        try {
            $siswa->delete();
            return back()->with('success', 'Data siswa berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menghapus siswa: ' . $e->getMessage());
        }
    }
    public function export()
    {
        return Excel::download(new SiswaExport, 'data_siswa_paud.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);



        try {
            Excel::import(new SiswaImport, $request->file('file'));
            return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diimport!');
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

}
