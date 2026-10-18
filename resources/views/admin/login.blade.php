<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Admin - Gawean</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --bs-body-font-family:'Inter', system-ui, sans-serif; --bs-body-color:#0A0A0A; --bs-link-color-rgb:10,10,10; --bs-link-hover-color-rgb:10,10,10; }
        body { min-height:100vh; display:flex; align-items:center; justify-content:center; padding:1rem; background:#0A0A0A;
               background-image:linear-gradient(#1C1B1B 1px, transparent 1px), linear-gradient(90deg, #1C1B1B 1px, transparent 1px); background-size:48px 48px; -webkit-font-smoothing:antialiased; }
        h4 { font-family:'Plus Jakarta Sans', sans-serif; font-weight:800; letter-spacing:-.02em; }
        .btn { font-weight:600; border-radius:10px; }
        .btn-primary { --bs-btn-bg:#0A0A0A; --bs-btn-border-color:#0A0A0A; --bs-btn-hover-bg:#262626; --bs-btn-hover-border-color:#262626; --bs-btn-active-bg:#0A0A0A; --bs-btn-active-border-color:#0A0A0A; }
        .form-control { border:1px solid #E5E5E5; border-radius:10px; padding:.65rem .9rem; }
        .form-control:focus { border-color:#0A0A0A; box-shadow:0 0 0 .5px #0A0A0A; }
        .alert-danger { background:#FEF2F2; border-color:#FECACA; color:#991B1B; border-radius:10px; }
        .g-login-card { width:100%; max-width:400px; background:#fff; border-radius:18px; padding:2rem; border:1px solid #fff; box-shadow:0 6px 0 0 #FACC15; }
        .g-login-icon { width:56px; height:56px; border-radius:14px; background:#0A0A0A; color:#FACC15; display:flex; align-items:center; justify-content:center; font-size:1.5rem; margin:0 auto 1rem; }
    </style>
</head>
<body>
    <div class="g-login-card">
        <div class="g-login-icon"><i class="bi bi-shield-lock-fill"></i></div>
        <h4 class="text-center mb-1">Admin Gawean</h4>
        <p class="text-center text-muted small mb-4">Masuk untuk mengelola platform</p>

        @error('email')
            <div class="alert alert-danger py-2 small">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="form-control" autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <button class="btn btn-primary w-100 py-2">Masuk sebagai Admin</button>
        </form>

        <p class="text-center small text-muted mt-4 mb-0">
            <a href="{{ route('home') }}" class="text-decoration-none">&larr; Kembali ke Gawean</a>
        </p>
    </div>
</body>
</html>
