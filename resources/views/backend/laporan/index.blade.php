@extends('layouts.master')
@section('content')

<div class="pagetitle">
    <h1>Manajemen Laporan PAUD</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Laporan PAUD</li>
        </ol>
    </nav>
</div>

<section class="section">
  <div class="card">
    <div class="card-body pt-3">

      {{-- Search dan Tambah --}}
      <div class="row mb-3">
        <div class="col-3">
          <form action="{{ route('laporan.index') }}" method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari periode atau lembaga..." value="{{ request('search') }}">
            <button class="btn btn-secondary">Cari</button>
          </form>
        </div>
        <div class="col-9 text-end">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Tambah Laporan</button>
        </div>
      </div>

      {{-- Tabel Data --}}
      <table class="table table-bordered table-striped table-sm">
        <thead>
          <tr>
            <th>No</th>
            <th>Lembaga</th>
            <th>Jenis</th>
            <th>Periode</th>
            <th>File</th>
            <th>Status</th>
            <th>Catatan</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $index => $item)
          <tr>
            <td>{{ $data->firstItem() + $index }}</td>
            <td>{{ $item->lembaga->nama_lembaga ?? '-' }}</td>
            <td>{{ ucfirst($item->jenis_laporan) }}</td>
            <td>{{ $item->periode }}</td>
            <td>
              <a href="{{ asset('storage/'.$item->file_laporan) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                Lihat File
              </a>
            </td>
            <td>
              @if($item->status_verifikasi == 'pending')
                <span class="badge bg-warning text-dark">Pending</span>
              @elseif($item->status_verifikasi == 'diterima')
                <span class="badge bg-success">Diterima</span>
              @else
                <span class="badge bg-danger">Ditolak</span>
              @endif
            </td>
            <td>{{ $item->catatan_verifikasi ?? '-' }}</td>
            <td>
              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">Edit</button>
              <form action="{{ route('laporan.destroy', $item->id) }}" method="POST" class="d-inline form-hapus">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
              </form>
              <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#verifModal{{ $item->id }}">Verifikasi</button>
            </td>
          </tr>

          {{-- Modal Edit --}}
          <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('laporan.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label>Jenis Laporan</label>
                      <select name="jenis_laporan" class="form-select" required>
                        @foreach(['bulanan','triwulan','semester','tahunan'] as $opt)
                          <option value="{{ $opt }}" {{ $item->jenis_laporan == $opt ? 'selected' : '' }}>
                            {{ ucfirst($opt) }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Periode</label>
                      <input type="text" name="periode" class="form-control" value="{{ $item->periode }}" required>
                    </div>
                    <div class="mb-3">
                      <label>Ganti File (Opsional)</label>
                      <input type="file" name="file_laporan" class="form-control" accept=".pdf,.doc,.docx">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

          {{-- Modal Verifikasi --}}
          <div class="modal fade" id="verifModal{{ $item->id }}" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <form action="{{ route('laporan.verifikasi', $item->id) }}" method="POST">
                  @csrf
                  <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <div class="mb-3">
                      <label>Status Verifikasi</label>
                      <select name="status_verifikasi" class="form-select" required>
                        <option value="pending" {{ $item->status_verifikasi == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="diterima" {{ $item->status_verifikasi == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ $item->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label>Catatan Verifikasi</label>
                      <textarea name="catatan_verifikasi" class="form-control" rows="3">{{ $item->catatan_verifikasi }}</textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Simpan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted">Belum ada data laporan</td>
          </tr>
          @endforelse
        </tbody>
      </table>

      <div class="justify-content-end mt-3">
        {{ $data->links('pagination::bootstrap-5') }}
      </div>

    </div>
  </div>
</section>

{{-- Modal Tambah --}}
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Laporan PAUD</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Lembaga</label>
            <select name="lembaga_id" class="form-select" required>
              <option value="">-- Pilih Lembaga --</option>
              @foreach ($lembaga as $l)
                <option value="{{ $l->id }}">{{ $l->nama_lembaga }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Jenis Laporan</label>
            <select name="jenis_laporan" class="form-select" required>
              <option value="bulanan">Bulanan</option>
              <option value="triwulan">Triwulan</option>
              <option value="semester">Semester</option>
              <option value="tahunan">Tahunan</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Periode</label>
            <input type="text" name="periode" class="form-control" placeholder="Contoh: Triwulan I 2025" required>
          </div>
          <div class="mb-3">
            <label>File Laporan (PDF/DOCX)</label>
            <input type="file" name="file_laporan" class="form-control" accept=".pdf,.doc,.docx" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- SweetAlert --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      showConfirmButton: false,
      timer: 2000
    });
  @endif

  @if (session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}'
    });
  @endif

  @if ($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Terjadi Kesalahan',
      html: `
        <ul style="text-align:left;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      `
    });
  @endif

  document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', function() {
      const form = this.closest('.form-hapus');
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data laporan akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });
});
</script>

@endsection
