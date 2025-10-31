<?php

namespace App\Http\Controllers;

use App\Models\LembagaPaud;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LembagaPaudController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $data = LembagaPaud::with('desa')
            ->when($search, function ($query, $search) {
                $query->where('nama_lembaga', 'like', "%{$search}%")
                      ->orWhere('npsn', 'like', "%{$search}%");
            })
            ->paginate(10);
        $desa = Desa::all();

        return view('backend.lembaga.index', compact('data', 'desa', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lembaga' => 'required|string|max:150',
            'npsn' => 'required|string|max:20|unique:lembaga_paud,npsn',
            'jenis_lembaga' => 'required|in:TK,KB,TPA,SPS',
            'status_izin' => 'required|in:izin,proses,belum',
            'akreditasi' => 'required|in:A,B,C,Belum',
            'alamat' => 'required',
            'desa_id' => 'required|exists:desa,id',
            'kepala_lembaga' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->all();

            // ✅ Upload foto jika ada
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('foto_lembaga', 'public');
                $data['foto'] = $path;
            }

            LembagaPaud::create($data);
            return redirect()->route('lembaga.index')->with('success', 'Lembaga PAUD berhasil ditambahkan.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menambahkan lembaga PAUD: ' . $e->getMessage());
        }
    }

    public function update(Request $request, LembagaPaud $lembaga)
    {
        $request->validate([
            'nama_lembaga' => 'required|string|max:150',
            'npsn' => 'required|string|max:20|unique:lembaga_paud,npsn,' . $lembaga->id,
            'jenis_lembaga' => 'required|in:TK,KB,TPA,SPS',
            'status_izin' => 'required|in:izin,proses,belum',
            'akreditasi' => 'required|in:A,B,C,Belum',
            'alamat' => 'required',
            'desa_id' => 'required|exists:desa,id',
            'kepala_lembaga' => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'latitude' => 'nullable|string|max:50',
            'longitude' => 'nullable|string|max:50',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $data = $request->all();

            // ✅ Ganti foto jika upload baru
            if ($request->hasFile('foto')) {
                if ($lembaga->foto && Storage::disk('public')->exists($lembaga->foto)) {
                    Storage::disk('public')->delete($lembaga->foto);
                }

                $path = $request->file('foto')->store('foto_lembaga', 'public');
                $data['foto'] = $path;
            }

            $lembaga->update($data);
            return redirect()->route('lembaga.index')->with('success', 'Data lembaga berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui lembaga: ' . $e->getMessage());
        }
    }

    public function destroy(LembagaPaud $lembaga)
    {
        try {
            if ($lembaga->foto && Storage::disk('public')->exists($lembaga->foto)) {
                Storage::disk('public')->delete($lembaga->foto);
            }
            $lembaga->delete();
            return redirect()->route('lembaga.index')->with('success', 'Lembaga berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menghapus lembaga: ' . $e->getMessage());
        }
    }
}
