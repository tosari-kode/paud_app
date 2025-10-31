<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DesaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $data = Desa::with('kecamatan')
            ->when($search, function ($query, $search) {
                return $query->where('nama_desa', 'like', "%{$search}%")
                             ->orWhereHas('kecamatan', function ($q) use ($search) {
                                 $q->where('nama_kecamatan', 'like', "%{$search}%");
                             });
            })
            ->orderBy('nama_desa')
            ->paginate(10);

        $kecamatan = Kecamatan::all();

        return view('backend.desa.index', compact('data', 'search', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:desa,kode',
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        try {
            Desa::create($request->only(['nama_desa', 'kode', 'kecamatan_id']));
            return redirect()->route('desa.index')->with('success', 'Desa berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menambahkan desa: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Desa $desa)
    {
        $request->validate([
            'nama_desa' => 'required|string|max:100',
            'kode' => 'required|string|max:20|unique:desa,kode,' . $desa->id,
            'kecamatan_id' => 'required|exists:kecamatan,id',
        ]);

        try {
            $desa->update($request->only(['nama_desa', 'kode', 'kecamatan_id']));
            return redirect()->route('desa.index')->with('success', 'Desa berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui desa: ' . $e->getMessage());
        }
    }

    public function destroy(Desa $desa)
    {
        try {
            $desa->delete();
            return redirect()->route('desa.index')->with('success', 'Desa berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menghapus desa: ' . $e->getMessage());
        }
    }
}
