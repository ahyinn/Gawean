<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') - Gawean</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --g-ink:#0A0A0A; --g-soft:#F5F5F5; --g-border:#E5E5E5; --g-muted:#6B7280;
            --g-yellow:#FACC15; --g-yellow-soft:#FEF9C3; --g-yellow-ink:#854D0E;
            --g-ok:#047857; --g-ok-bg:#ECFDF5; --g-ok-bd:#A7F3D0;
            --g-bad:#B91C1C; --g-bad-bg:#FEF2F2; --g-bad-bd:#FECACA;
            /* alias lama */
            --g-primary:#0A0A0A; --g-primary-dark:#0A0A0A; --g-text:#0A0A0A;
            --g-font-head:'Plus Jakarta Sans', system-ui, sans-serif;
            --bs-body-font-family:'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --bs-body-color:#0A0A0A; --bs-link-color-rgb:10,10,10; --bs-link-hover-color-rgb:10,10,10; --bs-border-color:#E5E5E5;
        }
        body { background:var(--g-soft); color:var(--g-ink); min-height:100vh; font-size:15px; -webkit-font-smoothing:antialiased; }
        h1,h2,h3,h4,h5,h6 { font-family:var(--g-font-head); letter-spacing:-.02em; font-weight:800; }
        :focus-visible { outline:2px solid var(--g-ink); outline-offset:2px; }
        .text-muted { color:var(--g-muted) !important; }
        .text-warning { color:#B45309 !important; }

        .btn { font-weight:600; font-size:.9rem; border-radius:10px; }
        .btn-sm { border-radius:8px; }
        .btn-primary { --bs-btn-color:#fff; --bs-btn-bg:#0A0A0A; --bs-btn-border-color:#0A0A0A; --bs-btn-hover-color:#fff; --bs-btn-hover-bg:#262626; --bs-btn-hover-border-color:#262626; --bs-btn-active-bg:#0A0A0A; --bs-btn-active-border-color:#0A0A0A; --bs-btn-disabled-bg:#0A0A0A; --bs-btn-disabled-border-color:#0A0A0A; }
        .btn-outline-primary { --bs-btn-color:#0A0A0A; --bs-btn-border-color:#0A0A0A; --bs-btn-hover-color:#0A0A0A; --bs-btn-hover-bg:#F5F5F5; --bs-btn-hover-border-color:#0A0A0A; --bs-btn-active-color:#0A0A0A; --bs-btn-active-bg:#F5F5F5; }
        .form-control, .form-select { border:1px solid var(--g-border); border-radius:10px; }
        .form-control:focus, .form-select:focus { border-color:var(--g-ink); box-shadow:0 0 0 .5px var(--g-ink); }
        .form-check-input:checked { background-color:var(--g-ink); border-color:var(--g-ink); }
        .form-check-input:focus { box-shadow:none; border-color:var(--g-ink); }
        .alert { border-radius:12px; font-size:.9rem; }
        .alert-success { background:var(--g-ok-bg); border-color:var(--g-ok-bd); color:#065F46; }
        .alert-danger { background:var(--g-bad-bg); border-color:var(--g-bad-bd); color:#991B1B; }
        .card { border:1px solid var(--g-border); border-radius:16px; }
        .pagination { gap:.4rem; flex-wrap:wrap; }
        .page-link { border:1px solid var(--g-border) !important; border-radius:10px !important; color:var(--g-ink); font-weight:600; }
        .page-link:focus { box-shadow:none; }
        .page-item.active .page-link { background:var(--g-ink); border-color:var(--g-ink) !important; color:#fff; }

        /* Kerangka admin */
        .g-admin-shell { display:flex; min-height:100vh; }
        .g-sidebar { width:250px; flex-shrink:0; background:var(--g-ink); color:#fff; padding:1.5rem 1rem; display:flex; flex-direction:column; position:sticky; top:0; height:100vh; }
        .g-sidebar-brand { display:flex; align-items:center; gap:.65rem; font-family:var(--g-font-head); font-weight:800; font-size:1.15rem; margin-bottom:2rem; color:#fff; text-decoration:none; letter-spacing:-.02em; }
        .g-sidebar-brand:hover { color:#fff; }
        .g-sidebar-icon { width:34px; height:34px; border-radius:10px; background:var(--g-yellow); color:var(--g-ink); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .g-sidebar a.g-nav-item { display:flex; align-items:center; gap:.65rem; color:#B5B3B3; text-decoration:none; padding:.65rem .85rem; border-radius:10px; font-weight:600; font-size:.92rem; margin-bottom:.25rem; border-left:3px solid transparent; }
        .g-sidebar a.g-nav-item:hover { background:#1C1B1B; color:#fff; }
        .g-sidebar a.g-nav-item.active { background:#1C1B1B; color:#fff; border-left-color:var(--g-yellow); }
        .g-sidebar-badge { background:var(--g-yellow); color:var(--g-ink); font-size:.7rem; font-weight:800; border-radius:999px; padding:.1rem .5rem; margin-left:auto; }
        .g-main { flex:1; padding:2rem; min-width:0; }

        .g-panel { background:#fff; border:1px solid var(--g-border); border-radius:16px; }
        .g-panel-table thead th { background:var(--g-soft); color:var(--g-ink); font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; font-weight:700; border-bottom:1px solid var(--g-border); padding:.95rem 1rem; }
        .g-panel-table tbody td { padding:.95rem 1rem; vertical-align:middle; border-color:var(--g-border); }
        .g-panel-table tbody tr:hover td { background:#FAFAFA; }

        .g-stat-card { background:#fff; border:1px solid var(--g-border); border-radius:16px; padding:1.25rem; height:100%; transition:border-color .18s, box-shadow .18s, transform .18s; }
        .g-stat-card:hover { border-color:var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); transform:translateY(-2px); }
        .g-stat-card .num { font-family:var(--g-font-head); font-size:2rem; font-weight:800; letter-spacing:-.03em; line-height:1.1; color:var(--g-ink); }
        .g-stat-card .label { color:var(--g-muted); font-size:.85rem; font-weight:600; }
        .g-stat-card.alert-card { background:var(--g-ink); border-color:var(--g-ink); }
        .g-stat-card.alert-card .num { color:var(--g-yellow); }
        .g-stat-card.alert-card .label { color:#C9C6C5; }

        /* Lencana status */
        .g-status-badge, .g-pay-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.28rem .7rem; border-radius:999px; font-size:.76rem; font-weight:700; border:1px solid transparent; white-space:nowrap; }
        .g-status-menunggu, .g-pay-warn { background:var(--g-yellow); color:var(--g-ink); border-color:var(--g-ink); }
        .g-status-diterima, .g-pay-info { background:var(--g-soft); color:var(--g-ink); border-color:var(--g-border); }
        .g-status-selesai, .g-pay-success { background:var(--g-ok-bg); color:var(--g-ok); border-color:var(--g-ok-bd); }
        .g-status-ditolak, .g-pay-danger { background:var(--g-bad-bg); color:var(--g-bad); border-color:var(--g-bad-bd); }
        .g-pay-primary { background:var(--g-ink); color:#fff; }
        .g-pay-muted { background:var(--g-soft); color:var(--g-muted); border-color:var(--g-border); }

        @media (max-width: 767.98px) {
            .g-admin-shell { flex-direction:column; }
            .g-sidebar { width:100%; height:auto; position:static; flex-direction:row; align-items:center; gap:1rem; overflow-x:auto; padding:1rem; }
            .g-sidebar-brand { margin-bottom:0; flex-shrink:0; }
            .g-sidebar nav { display:flex; gap:.4rem; }
            .g-sidebar a.g-nav-item { white-space:nowrap; margin-bottom:0; }
            .g-main { padding:1.25rem; }
        }
    </style>
</head>
<body>
@php $pendingPayments = \App\Models\Order::where('payment_status', 'menunggu_verifikasi')->count(); @endphp
<div class="g-admin-shell">
    <aside class="g-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="g-sidebar-brand">
            <span class="g-sidebar-icon"><i class="bi bi-shield-lock-fill"></i></span> Admin Gawean
        </a>
        <nav class="d-flex flex-md-column">
            <a href="{{ route('admin.dashboard') }}" class="g-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="{{ route('admin.users') }}" class="g-nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="bi bi-people-fill"></i> Pengguna</a>
            <a href="{{ route('admin.categories.index') }}" class="g-nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}"><i class="bi bi-grid-3x3-gap-fill"></i> Kategori</a>
            <a href="{{ route('admin.services') }}" class="g-nav-item {{ request()->routeIs('admin.services') ? 'active' : '' }}"><i class="bi bi-briefcase-fill"></i> Jasa</a>
            <a href="{{ route('admin.orders') }}" class="g-nav-item {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill"></i> Pesanan
                @if($pendingPayments > 0)
                    <span class="g-sidebar-badge">{{ $pendingPayments }}</span>
                @endif
            </a>
        </nav>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-md-auto pt-md-3 d-none d-md-block">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </aside>

    <main class="g-main">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h1 class="h3 mb-0">@yield('title')</h1>
            <form method="POST" action="{{ route('admin.logout') }}" class="d-md-none">
                @csrf
                <button class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</div>
</body>
</html>
