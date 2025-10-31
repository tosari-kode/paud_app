@extends('layouts.master')
@section('content')

<div class="pagetitle">
    <h1>Data Siswa PAUD</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Data Siswa</li>
        </ol>
    </nav>
</div>


<section class="section">
  <div class="card">
    <div class="card-body pt-3">
    <div class="row">
        <div class="col-6">
            <div class="d-flex justify-content-between mb-3">
                <form action="{{ route('siswa.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari siswa..." value="{{ $search }}">
                    <button class="btn btn-secondary">Cari</button>
                </form>
            </div>
        </div>
        <div class="col-6">
            <div class="text-end mb-2">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">+ Tambah Siswa</button>

          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel"></i>
              Import
          </button>

          <a href="{{ route('siswa.export') }}" class="btn btn-secondary">
            <i class="bi bi-cloud-arrow-down"></i>
            Export
          </a>


      </div>
        </div>
    </div>


      <table class="table table-bordered table-striped table-sm">
          <!-- <thead class="table-dark"> -->
          <thead>
              <tr>
                  <th>No</th>
                  <th>Nama Siswa</th>
                  <th>Jenis Kelamin</th>
                  <th>Tanggal Lahir</th>
                  <th>Tahun Masuk</th>
                  <th>Status</th>
                  <th>Lembaga</th>
                  <th>Aksi</th>
              </tr>
          </thead>
          <tbody>
              @foreach($siswa as $index => $item)
              <tr>
                  <td>{{ $siswa->firstItem() + $index }}</td>
                  <td>{{ $item->nama }}</td>
                  <td>{{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                  <td>{{ $item->tanggal_lahir }}</td>
                  <td>{{ $item->tahun_masuk }}</td>
                  <td>{{ ucfirst($item->status) }}</td>
                  <td>{{ $item->lembaga->nama_lembaga ?? '-' }}</td>
                  <td>
                      <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}">Edit</button>
                      <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="d-inline form-hapus">
                          @csrf
                          @method('DELETE')
                          <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                      </form>
                  </td>
              </tr>

              {{-- Modal Edit --}}
              <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
                  <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                          <form action="{{ route('siswa.update', $item->id) }}" method="POST">
                              @csrf
                              @method('PUT')
                              <div class="modal-header">
                                  <h5 class="modal-title">Edit Siswa</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                              </div>
                              <div class="modal-body">
                                  <div class="mb-3">
                                      <label>Nama Siswa</label>
                                      <input type="text" name="nama" class="form-control" value="{{ $item->nama }}" required>
                                  </div>
                                  <div class="mb-3">
                                      <label>Jenis Kelamin</label>
                                      <select name="jenis_kelamin" class="form-control">
                                          <option value="L" {{ $item->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                          <option value="P" {{ $item->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                      </select>
                                  </div>
                                  <div class="mb-3">
                                      <label>Tanggal Lahir</label>
                                      <input type="date" name="tanggal_lahir" class="form-control" value="{{ $item->tanggal_lahir }}" required>
                                  </div>
                                  <div class="mb-3">
                                      <label>Tahun Masuk</label>
                                      <input type="number" name="tahun_masuk" class="form-control" value="{{ $item->tahun_masuk }}" required>
                                  </div>
                                  <div class="mb-3">
                                      <label>Status</label>
                                      <select name="status" class="form-control">
                                          <option value="aktif" {{ $item->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                          <option value="lulus" {{ $item->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                                          <option value="pindah" {{ $item->status == 'pindah' ? 'selected' : '' }}>Pindah</option>
                                      </select>
                                  </div>
                                  <div class="mb-3">
                                      <label>Lembaga</label>
                                      <select name="lembaga_id" class="form-control" required>
                                          <option value="">-- Pilih Lembaga --</option>
                                          @foreach($lembaga as $l)
                                              <option value="{{ $l->id }}" {{ $item->lembaga_id == $l->id ? 'selected' : '' }}>
                                                  {{ $l->nama_lembaga }}
                                              </option>
                                          @endforeach
                                      </select>
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
              @endforeach
          </tbody>
      </table>

      <div class="justify-content-end mt-3">
          {{ $siswa->links('pagination::bootstrap-5') }}
        </div>

    </div>
  </div>
</section>

{{-- Modal Create --}}
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label>Nama Siswa</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Tahun Masuk</label>
                    <input type="number" name="tahun_masuk" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="lulus">Lulus</option>
                        <option value="pindah">Pindah</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label>Lembaga</label>
                    <select name="lembaga_id" class="form-control" required>
                        <option value="">-- Pilih Lembaga --</option>
                        @foreach($lembaga as $l)
                            <option value="{{ $l->id }}">{{ $l->nama_lembaga }}</option>
                        @endforeach
                    </select>
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
<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('siswa.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Import Data Siswa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Silakan upload file Excel (.xlsx) dengan format kolom berikut:</p>
          <ul>
            <li>lembaga_id</li>
            <li>nama</li>
            <li>jenis_kelamin</li>
            <li>tanggal_lahir</li>
            <li>tahun_masuk</li>
            <li>status</li>
          </ul>
          <div class="mb-3">
            <label for="file" class="form-label">Pilih File Excel</label>
            <input type="file" name="file" class="form-control" accept=".xlsx,.xls" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Import</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // === ALERT SUCCESS ===
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // === ALERT ERROR ===
    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
        });
    @endif

    // === VALIDATION ERROR ===
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
            `,
            confirmButtonColor: '#d33',
        });
    @endif

    // === KONFIRMASI HAPUS ===
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const form = this.closest('.form-hapus');
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data Siswa ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>

@endsection
