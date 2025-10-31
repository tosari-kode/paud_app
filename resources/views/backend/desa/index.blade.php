@extends('layouts.master')

@section('content')
<div class="pagetitle">
    <h1>Data Desa</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Data Wilayah Desa</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="card p-3 shadow-sm">

        {{-- 🔍 Search & Create --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <form action="{{ route('desa.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Cari desa..."
                       value="{{ $search ?? '' }}">
                <button class="btn btn-primary">Cari</button>
            </form>

            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createModal">
                + Tambah Desa
            </button>
        </div>

        {{-- ⚙️ Table --}}
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Desa</th>
                    <th>Kode</th>
                    <th>Kecamatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $desa)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $desa->nama_desa }}</td>
                    <td>{{ $desa->kode }}</td>
                    <td>{{ $desa->kecamatan->nama_kecamatan }}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $desa->id }}">Edit</button>

                        <form action="{{ route('desa.destroy', $desa->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>

                {{-- 🟡 Modal Edit --}}
                <div class="modal fade" id="editModal{{ $desa->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('desa.update', $desa->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Desa</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label>Nama Desa</label>
                                        <input type="text" name="nama_desa" class="form-control"
                                               value="{{ $desa->nama_desa }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode</label>
                                        <input type="text" name="kode" class="form-control"
                                               value="{{ $desa->kode }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kecamatan</label>
                                        <select name="kecamatan_id" class="form-control" required>
                                            @foreach ($kecamatan as $k)
                                                <option value="{{ $k->id }}" {{ $desa->kecamatan_id == $k->id ? 'selected' : '' }}>
                                                    {{ $k->nama_kecamatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- 📄 Pagination --}}
        <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
</section>

{{-- 🟢 Modal Create --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('desa.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Desa</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Desa</label>
                        <input type="text" name="nama_desa" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kode</label>
                        <input type="text" name="kode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Kecamatan</label>
                        <select name="kecamatan_id" class="form-control" required>
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kecamatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection
