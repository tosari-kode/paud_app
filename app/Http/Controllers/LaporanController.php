<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\LembagaPaud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    /**
     * Menampilkan semua laporan
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $data = Laporan::with('lembaga')
            ->when($search, function ($query, $search) {
                $query->where('periode', 'like', "%{$search}%")
                    ->orWhereHas('lembaga', function ($q) use ($search) {
                        $q->where('nama_lembaga', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $lembaga = LembagaPaud::all();

        return view('backend.laporan.index', compact('data', 'lembaga', 'search'));
    }

    /**
     * Simpan laporan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'lembaga_id' => 'required|exists:lembaga_paud,id',
            'jenis_laporan' => 'required|in:bulanan,triwulan,semester,tahunan',
            'periode' => 'required|string|max:50',
            'file_laporan' => 'required|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            $path = $request->file('file_laporan')->store('laporan', 'public');

            Laporan::create([
                'lembaga_id' => $request->lembaga_id,
                'jenis_laporan' => $request->jenis_laporan,
                'periode' => $request->periode,
                'file_laporan' => $path,
                'status_verifikasi' => 'pending',
            ]);

            return back()->with('success', 'Laporan berhasil diunggah dan menunggu verifikasi.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal menambahkan laporan: ' . $e->getMessage());
        }
    }

    /**
     * Update laporan
     */
    public function update(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);

        $request->validate([
            'jenis_laporan' => 'required|in:bulanan,triwulan,semester,tahunan',
            'periode' => 'required|string|max:50',
            'file_laporan' => 'nullable|mimes:pdf,doc,docx|max:2048',
        ]);

        try {
            if ($request->hasFile('file_laporan')) {
                // hapus file lama
                if ($laporan->file_laporan && Storage::disk('public')->exists($laporan->file_laporan)) {
                    Storage::disk('public')->delete($laporan->file_laporan);
                }
                $path = $request->file('file_laporan')->store('laporan', 'public');
                $laporan->file_laporan = $path;
            }

            $laporan->update([
                'jenis_laporan' => $request->jenis_laporan,
                'periode' => $request->periode,
                'file_laporan' => $laporan->file_laporan,
            ]);

            return back()->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui laporan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus laporan
     */
    public function destroy($id)
    {
        try {
            $laporan = Laporan::findOrFail($id);
            if ($laporan->file_laporan && Storage::disk('public')->exists($laporan->file_laporan)) {
                Storage::disk('public')->delete($laporan->file_laporan);
            }
            $laporan->delete();

            return response()->json(['success' => true, 'message' => 'Laporan berhasil dihapus.']);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json(['success' => false, 'message' => 'Gagal menghapus laporan.']);
        }
    }

    /**
     * Verifikasi laporan (Admin)
     */
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,diterima,ditolak',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        try {
            $laporan = Laporan::findOrFail($id);
            $laporan->update([
                'status_verifikasi' => $request->status_verifikasi,
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            return back()->with('success', 'Status verifikasi berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->with('error', 'Gagal memperbarui verifikasi: ' . $e->getMessage());
        }
    }
}
