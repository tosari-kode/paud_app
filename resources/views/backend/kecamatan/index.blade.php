@extends('layouts.master')

@section('content')
<div class="pagetitle">
  <h1>Data Wilayah</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
      <li class="breadcrumb-item active">Data Wilayah Kecamatan</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow-sm p-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5>Daftar Kecamatan</h5>
          <div class="d-flex">
            <button class="btn btn-primary btn-sm d-flex me-2" data-bs-toggle="modal" data-bs-target="#createModal">
              + Tambah Kecamatan
            </button>
            <form action="{{ route('kecamatan.index') }}" method="GET" class="d-flex me-2">
              <input type="text" name="search" class="form-control form-control-sm"
                     placeholder="Cari kecamatan..." value="{{ request('search') }}">
              <button type="submit" class="btn btn-sm btn-secondary ms-2">Cari</button>
            </form>
          </div>
        </div>

        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Kecamatan</th>
              <th>Kode Wilayah</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($data as $key => $item)
            <tr>
              <td>{{ $data->firstItem() + $key }}</td>
              <td>{{ $item->nama_kecamatan }}</td>
              <td>{{ $item->kode }}</td>
              <td>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $item->id }}">
                  Edit
                </button>
                <form action="{{ route('kecamatan.destroy', $item->id) }}" method="POST" class="d-inline form-hapus">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger btn-sm btn-hapus">Hapus</button>
                </form>
              </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="edit{{ $item->id }}" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <form action="{{ route('kecamatan.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                      <h5 class="modal-title">Edit Kecamatan</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                      <div class="mb-3">
                        <label>Nama Kecamatan</label>
                        <input type="text" name="nama_kecamatan" class="form-control" value="{{ $item->nama_kecamatan }}" required>
                      </div>
                      <div class="mb-3">
                        <label>Kode Wilayah</label>
                        <input type="text" name="kode" class="form-control" value="{{ $item->kode }}" required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-warning">Update</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            @empty
            <tr>
              <td colspan="4" class="text-center">Tidak ada data kecamatan.</td>
            </tr>
            @endforelse
          </tbody>
        </table>

        <!-- Pagination -->
        <div class="justify-content-end mt-3">
          {{ $data->links('pagination::bootstrap-5') }}
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('kecamatan.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kecamatan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama Kecamatan</label>
            <input type="text" class="form-control" name="nama_kecamatan" required>
          </div>
          <div class="mb-3">
            <label>Kode Wilayah</label>
            <input type="text" class="form-control" name="kode" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {

  // === SUCCESS ALERT ===
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      showConfirmButton: false,
      timer: 2000
    });
  @endif

  // === ERROR ALERT ===
  @if (session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}',
      confirmButtonColor: '#d33'
    });
  @endif

  // === VALIDATION ERROR ALERT ===
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
        text: "Data kecamatan ini akan dihapus permanen!",
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
