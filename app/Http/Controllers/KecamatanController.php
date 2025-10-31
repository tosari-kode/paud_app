<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KecamatanController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil query pencarian (kalau ada)
            $search = $request->input('search');

            // Query dasar
            $query = Kecamatan::query();

            // Jika ada pencarian
            if ($search) {
                $query->where('nama_kecamatan', 'like', '%' . $search . '%')
                    ->orWhere('kode', 'like', '%' . $search . '%');
            }

            // Pagination Laravel (10 per halaman)
            $data = $query->orderBy('nama_kecamatan', 'asc')->paginate(10);

            // Menyertakan parameter search di pagination
            $data->appends(['search' => $search]);

            return view('backend.kecamatan.index', compact('data', 'search'));
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:kecamatan,kode',
        ]);

        try {
            Kecamatan::create($request->only(['nama_kecamatan', 'kode']));
            return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menambahkan kecamatan: ' . $e->getMessage());
        }
    }



    public function update(Request $request, Kecamatan $kecamatan)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:kecamatan,kode,' . $kecamatan->id,
        ]);

        try {
            $kecamatan->update($request->only(['nama_kecamatan', 'kode']));
            return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui kecamatan: ' . $e->getMessage());
        }
    }

    public function destroy(Kecamatan $kecamatan)
    {
        try {
            $kecamatan->delete();
            return redirect()->route('kecamatan.index')->with('success', 'Kecamatan berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menghapus kecamatan: ' . $e->getMessage());
        }
    }
}
