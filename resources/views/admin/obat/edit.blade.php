@extends('layouts.app')
@section('title', 'Edit Obat')
@section('page-title', 'Edit Obat')

@section('content')
<div class="row justify-content-center">
<div class="col-md-7">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Edit Obat — {{ $obat->kode_obat }}</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.obat.update', $obat) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                    <input type="text" name="nama_obat" class="form-control" value="{{ old('nama_obat', $obat->nama_obat) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Satuan <span class="text-danger">*</span></label>
                    <select name="satuan" class="form-select" required>
                        @foreach(['tablet','kapsul','botol','sachet','ampul','vial','tube','strip'] as $s)
                        <option value="{{ $s }}" {{ $obat->satuan==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="{{ old('kategori', $obat->kategori) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                    <input type="number" name="stok" class="form-control" value="{{ old('stok', $obat->stok) }}" min="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                    <input type="number" name="stok_minimum" class="form-control" value="{{ old('stok_minimum', $obat->stok_minimum) }}" min="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_beli" class="form-control" value="{{ old('harga_beli', $obat->harga_beli) }}" min="0" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga_jual" class="form-control" value="{{ old('harga_jual', $obat->harga_jual) }}" min="0" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tanggal Kadaluarsa</label>
                    <input type="date" name="tanggal_kadaluarsa" class="form-control"
                           value="{{ old('tanggal_kadaluarsa', $obat->tanggal_kadaluarsa?->format('Y-m-d')) }}">
                </div>
                <div class="col-12 mt-2">
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Update</button>
                    <a href="{{ route('admin.obat.index') }}" class="btn btn-secondary ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection
