@extends('layouts.master')
@section('content')

<div class="pagetitle">
    <h1>Data Lembaga PAUD</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Lembaga PAUD</li>
        </ol>
    </nav>
</div>

<section class="section">
<div class="card p-3">

    <div class="d-flex justify-content-between mb-3">
        <form method="GET" class="d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Cari lembaga..."
                   value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Cari</button>
        </form>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">+ Tambah</button>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>lembaga_id</th>
                <th>Nama Lembaga</th>
                <th>NPSN</th>
                <th>Jenis</th>
                <th>Desa</th>
                <th>Akreditasi</th>
                <th>Lokasi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $row)
                <tr>
                    <td>{{ $data->firstItem() + $i }}</td>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->nama_lembaga }}</td>
                    <td>{{ $row->npsn }}</td>
                    <td>{{ $row->jenis_lembaga }}</td>
                    <td>{{ $row->desa->nama_desa ?? '-' }}</td>
                    <td>{{ $row->akreditasi }}</td>
                    <td>
                        @if($row->latitude && $row->longitude)
                            <a href="https://www.google.com/maps?q={{ $row->latitude }},{{ $row->longitude }}" target="_blank">Lihat</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">Edit</button>

                        <form action="{{ route('lembaga.destroy', $row->id) }}" method="POST" class="d-inline form-hapus">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-danger btn-hapus">Hapus</button>
                        </form>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('lembaga.update', $row->id) }}" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Lembaga</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    @include('backend.lembaga.partials.form', ['data' => $row, 'desa' => $desa])
                                </div>
                                <div class="modal-footer">
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
        {{ $data->links('pagination::bootstrap-5') }}
    </div>
</div>
</section>

{{-- Modal Create --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('lembaga.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lembaga PAUD</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('backend.lembaga.partials.form', ['data' => null, 'desa' => $desa])
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert2 --}}

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
                text: "Data lembaga ini akan dihapus permanen!",
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
