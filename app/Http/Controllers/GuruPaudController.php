<?php

namespace App\Http\Controllers;

use App\Models\GuruPaud;
use App\Models\LembagaPaud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GuruPaudController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $guru = GuruPaud::with('lembaga')
                ->when($search, function ($query, $search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('nuptk', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10);

        $lembaga = LembagaPaud::all();

        return view('backend.guru_paud.index', compact('guru', 'lembaga', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lembaga_id' => 'required|exists:lembaga_paud,id',
            'nama' => 'required|string|max:100',
            'nuptk' => 'required|string|max:30|unique:guru_paud,nuptk',
            'pendidikan_terakhir' => 'required|string|max:50',
            'status_guru' => 'required|in:PNS,Honorer,Kontrak',
            'jenis_kelamin' => 'required|in:L,P',
            'sertifikasi' => 'required|boolean',
            'pelatihan_terakhir' => 'nullable|string|max:255',
            'tahun_masuk' => 'nullable|digits:4',
        ]);

        try {
            GuruPaud::create($request->all());
            return back()->with('success', 'Data guru berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menambahkan guru: ' . $e->getMessage());
        }
    }

    public function update(Request $request, GuruPaud $guru)
    {
        $request->validate([
            'lembaga_id' => 'required|exists:lembaga_paud,id',
            'nama' => 'required|string|max:100',
            'nuptk' => 'required|string|max:30|unique:guru_paud,nuptk,' . $guru->id,
            'pendidikan_terakhir' => 'required|string|max:50',
            'status_guru' => 'required|in:PNS,Honorer,Kontrak',
            'jenis_kelamin' => 'required|in:L,P',
            'sertifikasi' => 'required|boolean',
            'pelatihan_terakhir' => 'nullable|string|max:255',
            'tahun_masuk' => 'nullable|digits:4',
        ]);

        try {
            $guru->update($request->all());
            return back()->with('success', 'Data guru berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui guru: ' . $e->getMessage());
        }
    }

    public function destroy(GuruPaud $guru)
    {
        try {
            $guru->delete();
            return back()->with('success', 'Data guru berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menghapus guru: ' . $e->getMessage());
        }
    }
}
