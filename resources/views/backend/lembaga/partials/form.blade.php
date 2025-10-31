<div class="row g-3">
    <div class="col-md-6">
        <label>Nama Lembaga</label>
        <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $data->nama_lembaga ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>NPSN</label>
        <input type="text" name="npsn" value="{{ old('npsn', $data->npsn ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>Jenis Lembaga</label>
        <select name="jenis_lembaga" class="form-select" required>
            @foreach(['TK','KB','TPA','SPS'] as $j)
                <option value="{{ $j }}" @selected(old('jenis_lembaga', $data->jenis_lembaga ?? '') == $j)>{{ $j }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label>Desa</label>
        <select name="desa_id" class="form-select" required>
            <option value="">-- Pilih Desa --</option>
            @foreach($desa as $d)
                <option value="{{ $d->id }}" @selected(old('desa_id', $data->desa_id ?? '') == $d->id)>{{ $d->nama_desa }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label>Status Izin</label>
        <select name="status_izin" class="form-select" required>
            @foreach(['izin','proses','belum'] as $s)
                <option value="{{ $s }}" @selected(old('status_izin', $data->status_izin ?? '') == $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label>Akreditasi</label>
        <select name="akreditasi" class="form-select" required>
            @foreach(['A','B','C','Belum'] as $a)
                <option value="{{ $a }}" @selected(old('akreditasi', $data->akreditasi ?? '') == $a)>{{ $a }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-12">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" rows="2" required>{{ old('alamat', $data->alamat ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label>Kepala Lembaga</label>
        <input type="text" name="kepala_lembaga" value="{{ old('kepala_lembaga', $data->kepala_lembaga ?? '') }}" class="form-control" required>
    </div>
    <div class="col-md-6">
        <label>No HP</label>
        <input type="text" name="no_hp" value="{{ old('no_hp', $data->no_hp ?? '') }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email', $data->email ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Latitude</label>
        <input type="text" name="latitude" value="{{ old('latitude', $data->latitude ?? '') }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label>Longitude</label>
        <input type="text" name="longitude" value="{{ old('longitude', $data->longitude ?? '') }}" class="form-control">
    </div>
    <div class="col-md-12">
        <label>Foto</label>
        <input type="file" name="foto" class="form-control">
        @if(isset($data) && $data->foto)
            <img src="{{ asset('storage/'.$data->foto) }}" class="img-fluid mt-2 rounded" width="100">
        @endif
    </div>
</div>
