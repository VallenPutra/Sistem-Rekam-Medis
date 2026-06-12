@extends('layouts.app')
@section('title', 'Edit Akun')
@section('page-title', 'Edit Akun')

@section('content')
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Edit Akun — {{ $user->name }}</strong></div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert alert-danger">@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
        @endif
        <form method="POST" action="{{ route('admin.akun.update', $user) }}">
            @csrf @method('PUT')
            <div class="mb-3">
                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select" required id="roleSelect">
                    <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                    <option value="dokter" {{ $user->role=='dokter'?'selected':'' }}>Dokter</option>
                </select>
            </div>
            <div class="mb-3" id="spesialisField" style="{{ $user->role=='dokter'?'':'display:none' }}">
                <label class="form-label">Spesialis</label>
                <input type="text" name="spesialis" class="form-control" value="{{ old('spesialis', $user->spesialis) }}">
            </div>
            <div class="mb-3">
                <label class="form-label">No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
            </div>
            <hr>
            <p class="text-muted small">Kosongkan password jika tidak ingin diubah.</p>
            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" minlength="6">
            </div>
            <div class="mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
            <button type="submit" class="btn btn-warning"><i class="fa fa-save me-1"></i>Update</button>
            <a href="{{ route('admin.akun.index') }}" class="btn btn-secondary ms-2">Batal</a>
        </form>
    </div>
</div>
</div>
</div>
@push('scripts')
<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    document.getElementById('spesialisField').style.display = this.value === 'dokter' ? '' : 'none';
});
</script>
@endpush
@endsection
