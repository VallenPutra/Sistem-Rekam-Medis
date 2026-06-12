@extends('layouts.app')
@section('title', 'Edit Pasien')
@section('page-title', 'Edit Data Pasien')

@section('content')
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Edit Data Pasien — {{ $pasien->no_rm }}</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('admin.pasien.update', $pasien) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $pasien->nama) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="L" {{ $pasien->jenis_kelamin=='L'?'selected':'' }}>Laki-laki</option>
                        <option value="P" {{ $pasien->jenis_kelamin=='P'?'selected':'' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $pasien->nik) }}" maxlength="16">
                </div>
                <div class="col-md-6">
                    <label class="form-label">No HP</label>
                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pasien->no_hp) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $pasien->alamat) }}</textarea>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Golongan Darah</label>
                    <select name="golongan_darah" class="form-select">
                        <option value="">-</option>
                        @foreach(['A','B','AB','O'] as $gb)
                        <option value="{{ $gb }}" {{ $pasien->golongan_darah==$gb?'selected':'' }}>{{ $gb }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jenis Pembayaran <span class="text-danger">*</span></label>
                    <select name="jenis_pembayaran" class="form-select" required id="jenisBayar">
                        <option value="umum" {{ $pasien->jenis_pembayaran=='umum'?'selected':'' }}>Umum</option>
                        <option value="bpjs" {{ $pasien->jenis_pembayaran=='bpjs'?'selected':'' }}>BPJS</option>
                    </select>
                </div>
                <div class="col-md-5" id="bpjsField" style="{{ $pasien->jenis_pembayaran=='bpjs' ? '' : 'display:none' }}">
                    <label class="form-label">No BPJS</label>
                    <input type="text" name="no_bpjs" class="form-control" value="{{ old('no_bpjs', $pasien->no_bpjs) }}">
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Update</button>
                    <a href="{{ route('admin.pasien.show', $pasien) }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@push('scripts')
<script>
document.getElementById('jenisBayar').addEventListener('change', function() {
    document.getElementById('bpjsField').style.display = this.value === 'bpjs' ? '' : 'none';
});
</script>
@endpush
@endsection
