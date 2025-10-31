@extends('layouts.master')
@section('content')

<div class="pagetitle">
    <h1>Data Guru PAUD</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Data Guru PAUD</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card p-4 shadow-sm">

        <div class="d-flex justify-content-between align-items-center mb-2">
            <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
                + Tambah Guru
            </button>
            <form method="GET" action="{{ route('guru.index') }}" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari nama atau NUPTK..."
                       value="{{ $search }}">
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        {{-- ✅ Alert sukses/gagal biasa --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>NUPTK</th>
                        <th>Pendidikan</th>
                        <th>Status</th>
                        <th>JK</th>
                        <th>Lembaga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($guru as $g)
                        <tr>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->nuptk }}</td>
                            <td>{{ $g->pendidikan_terakhir }}</td>
                            <td>{{ $g->status_guru }}</td>
                            <td>{{ $g->jenis_kelamin }}</td>
                            <td>{{ $g->lembaga->nama_lembaga ?? '-' }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $g->id }}">Edit</button>

                                <form action="{{ route('guru.destroy', $g->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm btn-delete">Hapus</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div class="modal fade" id="editModal{{ $g->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('guru.update', $g->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Guru</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2">
                                                <label>Lembaga</label>
                                                <select name="lembaga_id" class="form-control" required>
                                                    @foreach($lembaga as $l)
                                                        <option value="{{ $l->id }}" {{ $l->id == $g->lembaga_id ? 'selected' : '' }}>
                                                            {{ $l->nama_lembaga }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label>Nama</label>
                                                <input type="text" name="nama" class="form-control" value="{{ $g->nama }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>NUPTK</label>
                                                <input type="text" name="nuptk" class="form-control" value="{{ $g->nuptk }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label>Pendidikan</label>
                                                <input type="text" name="pendidikan_terakhir" class="form-control" value="{{ $g->pendidikan_terakhir }}">
                                            </div>
                                            <div class="mb-2">
                                                <label>Status</label>
                                                <select name="status_guru" class="form-control">
                                                    <option {{ $g->status_guru == 'PNS' ? 'selected' : '' }}>PNS</option>
                                                    <option {{ $g->status_guru == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                                                    <option {{ $g->status_guru == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label>Jenis Kelamin</label>
                                                <select name="jenis_kelamin" class="form-control">
                                                    <option value="L" {{ $g->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                    <option value="P" {{ $g->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label>Sertifikasi</label>
                                                <select name="sertifikasi" class="form-control">
                                                    <option value="1" {{ $g->sertifikasi ? 'selected' : '' }}>Sudah</option>
                                                    <option value="0" {{ !$g->sertifikasi ? 'selected' : '' }}>Belum</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label>Pelatihan Terakhir</label>
                                                <input type="text" name="pelatihan_terakhir" class="form-control" value="{{ $g->pelatihan_terakhir }}">
                                            </div>
                                            <div class="mb-2">
                                                <label>Tahun Masuk</label>
                                                <input type="number" name="tahun_masuk" class="form-control" value="{{ $g->tahun_masuk }}">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada data guru.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="justify-content-end mt-3">
            {{ $guru->links('pagination::bootstrap-5') }}
        </div>
    </div>
</section>

{{-- Modal Tambah --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('guru.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Lembaga</label>
                        <select name="lembaga_id" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            @foreach($lembaga as $l)
                                <option value="{{ $l->id }}">{{ $l->nama_lembaga }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>NUPTK</label>
                        <input type="text" name="nuptk" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Pendidikan Terakhir</label>
                        <input type="text" name="pendidikan_terakhir" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Status</label>
                        <select name="status_guru" class="form-control">
                            <option value="PNS">PNS</option>
                            <option value="Honorer">Honorer</option>
                            <option value="Kontrak">Kontrak</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Sertifikasi</label>
                        <select name="sertifikasi" class="form-control">
                            <option value="1">Sudah</option>
                            <option value="0">Belum</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Pelatihan Terakhir</label>
                        <input type="text" name="pelatihan_terakhir" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Tahun Masuk</label>
                        <input type="number" name="tahun_masuk" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    @elseif (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
        });
    @endif

    // 🔥 Konfirmasi hapus
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            let form = this.closest('.delete-form');
            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
});
</script>

@endsection
