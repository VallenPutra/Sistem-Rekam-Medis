<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perbarui Password Baru - Klinik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Atur Password Baru</h5>

                    @if($errors->any())
                        <div class="alert alert-danger small">
                            @foreach($errors->all() as $e)
                                <div>{{ $e }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Konfirmasi</label>
                            <input type="email" name="email" class="form-control" required placeholder="Masukkan email kamu lagi" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password Baru</label>
                            <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Perbarui Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>