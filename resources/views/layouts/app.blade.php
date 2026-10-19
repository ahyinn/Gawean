<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gawean')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* =====================================================
           GAWEAN — Editorial Brutalism (putih, hitam, kuning)
           ===================================================== */
        :root {
            --g-ink:#0A0A0A; --g-white:#FFFFFF; --g-soft:#F5F5F5; --g-border:#E5E5E5; --g-muted:#6B7280;
            --g-yellow:#FACC15; --g-yellow-soft:#FEF9C3; --g-yellow-ink:#854D0E;

            --g-ok:#047857;   --g-ok-bg:#ECFDF5;   --g-ok-bd:#A7F3D0;
            --g-wait:#B45309; --g-wait-bg:#FEF3C7; --g-wait-bd:#FDE68A;
            --g-bad:#B91C1C;  --g-bad-bg:#FEF2F2;  --g-bad-bd:#FECACA;

            /* Nama variabel lama dipertahankan supaya halaman lain (jasa saya, form, dll) ikut berubah otomatis */
            --g-primary:#0A0A0A; --g-primary-dark:#0A0A0A; --g-text:#0A0A0A;

            --g-font-head:'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', sans-serif;
            --g-font-body:'Inter', system-ui, -apple-system, 'Segoe UI', sans-serif;

            --bs-body-font-family:var(--g-font-body);
            --bs-body-color:#0A0A0A;
            --bs-link-color-rgb:10,10,10;
            --bs-link-hover-color-rgb:10,10,10;
            --bs-border-color:#E5E5E5;
            --bs-link-color:#0A0A0A; --bs-link-hover-color:#0A0A0A;
        }

        html, body { height:100%; }
        body { background:#fff; color:var(--g-ink); display:flex; flex-direction:column; min-height:100vh; overflow-x:hidden; font-size:15px; line-height:1.6; -webkit-font-smoothing:antialiased; }
        main.g-main { flex:1 0 auto; width:100%; }
        .g-footer { flex-shrink:0; }
        .container.g-container { max-width:1280px; }
        h1,h2,h3,h4,h5,h6,.g-head { font-family:var(--g-font-head); letter-spacing:-.02em; }
        h1,h2,h3,h4,h5 { font-weight:800; }
        a { text-underline-offset:3px; }
        :focus-visible { outline:2px solid var(--g-ink); outline-offset:2px; }
        .text-muted { color:var(--g-muted) !important; }
        .text-primary { color:var(--g-ink) !important; }
        .g-star { color:var(--g-yellow); -webkit-text-stroke:.6px var(--g-ink); }

        /* ---------- Tombol ---------- */
        .btn { font-weight:600; font-size:.92rem; border-radius:10px; padding:.62rem 1.15rem; transition:transform .15s, box-shadow .15s, background .15s, color .15s, border-color .15s; }
        .btn-sm { padding:.4rem .85rem; font-size:.82rem; border-radius:8px; }
        .btn-primary { --bs-btn-color:#fff; --bs-btn-bg:#0A0A0A; --bs-btn-border-color:#0A0A0A; --bs-btn-hover-color:#fff; --bs-btn-hover-bg:#262626; --bs-btn-hover-border-color:#262626; --bs-btn-active-color:#fff; --bs-btn-active-bg:#0A0A0A; --bs-btn-active-border-color:#0A0A0A; --bs-btn-disabled-bg:#0A0A0A; --bs-btn-disabled-border-color:#0A0A0A; }
        .btn-primary:hover { transform:translateY(-1px); }
        .btn-primary:active { transform:translateY(0); }
        .btn-outline-primary { --bs-btn-color:#0A0A0A; --bs-btn-border-color:#0A0A0A; --bs-btn-hover-color:#0A0A0A; --bs-btn-hover-bg:#F5F5F5; --bs-btn-hover-border-color:#0A0A0A; --bs-btn-active-color:#0A0A0A; --bs-btn-active-bg:#F5F5F5; --bs-btn-active-border-color:#0A0A0A; --bs-btn-disabled-color:#0A0A0A; --bs-btn-disabled-border-color:#0A0A0A; }
        .btn-outline-primary:hover { box-shadow:0 3px 0 0 #0A0A0A; transform:translateY(-1px); }
        .btn-accent { background:var(--g-yellow); color:var(--g-ink); border:1px solid var(--g-ink); font-weight:700; }
        .btn-accent:hover { background:#FDE047; color:var(--g-ink); box-shadow:0 3px 0 0 var(--g-ink); transform:translateY(-1px); }
        .btn-accent:active { transform:translateY(0); box-shadow:0 1px 0 0 var(--g-ink); }
        .btn-link { --bs-btn-color:var(--g-ink); --bs-btn-hover-color:var(--g-ink); --bs-btn-active-color:var(--g-ink); --bs-btn-disabled-color:var(--g-muted); text-decoration:underline; text-decoration-color:var(--g-yellow); text-decoration-thickness:2px; text-underline-offset:4px; }
        .btn-pill { border-radius:999px; }
        .g-btn-join:hover { background:var(--g-yellow) !important; color:var(--g-ink) !important; border-color:var(--g-ink) !important; }

        /* ---------- Form ---------- */
        .form-label { font-weight:600; font-size:.85rem; margin-bottom:.4rem; }
        .form-control, .form-select { border:1px solid var(--g-border); border-radius:10px; padding:.65rem .9rem; font-size:.95rem; color:var(--g-ink); }
        .form-control::placeholder { color:var(--g-muted); }
        .form-control:focus, .form-select:focus { border-color:var(--g-ink); box-shadow:0 0 0 .5px var(--g-ink); }
        .form-check-input { width:1.2rem; height:1.2rem; border:1.5px solid #D4D4D4; margin-top:.15rem; }
        .form-check-input[type=checkbox] { border-radius:6px; }
        .form-check-input:checked { background-color:var(--g-ink); border-color:var(--g-ink); }
        .form-check-input:focus { box-shadow:none; border-color:var(--g-ink); }
        .form-text { color:var(--g-muted); font-size:.8rem; }

        .alert { border-radius:12px; font-size:.9rem; border-width:1px; }
        .alert-success { background:var(--g-ok-bg); border-color:var(--g-ok-bd); color:#065F46; }
        .alert-danger { background:var(--g-bad-bg); border-color:var(--g-bad-bd); color:#991B1B; }
        .alert-secondary { background:var(--g-soft); border-color:var(--g-border); color:var(--g-ink); }

        /* Halaman lama yang masih memakai komponen Bootstrap .card / .table */
        .card { border:1px solid var(--g-border); border-radius:16px; }
        .card .table thead th { background:var(--g-soft); font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; font-weight:700; border-bottom:0; padding:.9rem 1rem; color:var(--g-ink); }
        .card .table tbody td { padding:.9rem 1rem; border-color:var(--g-border); }
        .badge.rounded-pill { font-weight:600; }

        /* ---------- Navbar ---------- */
        .g-nav { background:#fff; border-bottom:1px solid var(--g-border); position:sticky; top:0; z-index:1030; }
        .g-nav .navbar { padding:.8rem 0; }
        .g-brand { display:inline-flex; align-items:center; gap:.6rem; font-family:var(--g-font-head); font-weight:800; font-size:1.3rem; letter-spacing:-.02em; color:var(--g-ink); text-decoration:none; }
        .g-brand:hover { color:var(--g-ink); }
        .g-brand-icon { width:34px; height:34px; border-radius:10px; background:var(--g-ink); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:1rem; flex-shrink:0; }
        .g-nav-links { display:flex; align-items:center; gap:1.75rem; }
        .g-nav-actions { display:flex; align-items:center; gap:.75rem; }
        .g-link, button.g-link { color:var(--g-muted); font-weight:600; font-size:.92rem; text-decoration:none; white-space:nowrap; padding:.35rem 0; border:0; border-bottom:2px solid transparent; background:none; cursor:pointer; transition:color .15s; }
        .g-link:hover, button.g-link:hover { color:var(--g-ink); }
        .g-link.active { color:var(--g-ink); font-weight:700; border-bottom-color:var(--g-ink); }
        .g-nav-user { display:inline-flex; align-items:center; gap:.5rem; background:var(--g-soft); border:1px solid var(--g-border); border-radius:999px; padding:.28rem .85rem .28rem .3rem; font-size:.85rem; font-weight:600; white-space:nowrap; color:var(--g-ink); text-decoration:none; max-width:200px; }
        .g-nav-user:hover { border-color:var(--g-ink); color:var(--g-ink); }
        .g-nav-user .nm { overflow:hidden; text-overflow:ellipsis; }
        .g-nav-icons { display:flex; align-items:center; gap:.5rem; }
        .g-nav-icon { position:relative; width:40px; height:40px; border-radius:50%; border:1px solid var(--g-border); background:#fff; color:var(--g-ink); display:inline-flex; align-items:center; justify-content:center; font-size:1.05rem; text-decoration:none; flex-shrink:0; transition:border-color .15s, background .15s; }
        .g-nav-icon:hover { border-color:var(--g-ink); background:var(--g-soft); color:var(--g-ink); }
        .g-nav-icon.active { background:var(--g-ink); border-color:var(--g-ink); color:#fff; }
        .g-badge { position:absolute; top:-6px; right:-6px; min-width:19px; height:19px; padding:0 5px; border-radius:999px; background:var(--g-yellow); color:var(--g-ink); border:1.5px solid var(--g-ink); font-family:var(--g-font-head); font-size:.66rem; font-weight:800; line-height:16px; text-align:center; }
        .g-badge[hidden] { display:none; }
        @media (min-width: 992px) and (max-width: 1199.98px) {
            .g-nav-links { gap:1rem; }
            .g-link, button.g-link { font-size:.86rem; }
            .g-nav-actions { gap:.5rem; }
            .g-nav-user .nm { display:none; }
            .g-nav-user { padding:.28rem; }
            .g-nav .btn-sm { padding:.4rem .7rem; }
        }
        .navbar-toggler { border:1px solid var(--g-border); border-radius:10px; padding:.35rem .6rem; }
        .navbar-toggler:focus { box-shadow:none; border-color:var(--g-ink); }
        @media (max-width: 991.98px) {
            #gNavMenu { border-top:1px solid var(--g-border); margin-top:.85rem; padding-top:1rem; }
            .g-nav-links { flex-direction:column; align-items:flex-start; gap:.9rem; margin-bottom:1rem; }
            .g-nav-actions { flex-direction:column; align-items:stretch; }
            .g-nav-actions .btn, .g-nav-actions form { width:100%; }
            .g-nav-user { align-self:flex-start; }
        }

        /* ---------- Komponen umum ---------- */
        .g-crumbs { display:flex; align-items:center; flex-wrap:wrap; gap:.5rem; font-size:.82rem; color:var(--g-muted); }
        .g-crumbs a { color:var(--g-muted); text-decoration:none; }
        .g-crumbs a:hover { color:var(--g-ink); text-decoration:underline; }
        .g-crumbs .cur { color:var(--g-ink); font-weight:600; }
        .g-page-title { font-size:clamp(1.8rem,3.2vw,2.5rem); line-height:1.15; margin:0; }
        .g-page-sub { color:var(--g-muted); max-width:560px; margin:.5rem 0 0; }
        .g-section-title { font-size:clamp(1.5rem,2.6vw,2.25rem); line-height:1.2; margin:0; }
        .g-kicker { display:inline-flex; align-items:center; gap:.5rem; font-family:var(--g-font-head); font-size:.72rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:var(--g-yellow-ink); margin-bottom:.35rem; }
        .g-kicker::before { content:""; width:9px; height:9px; border-radius:50%; background:var(--g-yellow); }

        .g-panel { background:#fff; border:1px solid var(--g-border); border-radius:16px; overflow:hidden; }
        .g-panel-pad { padding:1.5rem; }
        .g-panel-title { font-size:1.15rem; font-weight:800; margin:0 0 1rem; padding-bottom:.9rem; border-bottom:1px solid var(--g-border); }
        .g-panel-table thead th { background:var(--g-soft); color:var(--g-ink); font-size:.72rem; text-transform:uppercase; letter-spacing:.06em; font-weight:700; border-bottom:1px solid var(--g-border); padding:.95rem 1rem; white-space:nowrap; }
        .g-panel-table tbody td { padding:1rem; vertical-align:middle; border-color:var(--g-border); }
        .g-panel-table tbody tr:last-child td { border-bottom:0; }
        .g-panel-table tbody tr:hover td { background:#FAFAFA; }

        .g-empty-state { text-align:center; padding:3.5rem 1.5rem; color:var(--g-muted); }
        .g-empty-state .ico { width:64px; height:64px; border-radius:50%; background:var(--g-soft); border:1px solid var(--g-border); color:var(--g-ink); display:inline-flex; align-items:center; justify-content:center; font-size:1.6rem; margin-bottom:1rem; }
        .g-empty-state h5 { color:var(--g-ink); margin-bottom:.4rem; }
        .g-empty-state p { margin:0 auto 1.25rem; max-width:420px; }

        .g-avatar { width:30px; height:30px; border-radius:50%; background:var(--g-ink); color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:700; flex-shrink:0; }
        .g-fm-avatar { border-radius:50%; background:var(--g-ink); color:#fff; display:flex; align-items:center; justify-content:center; font-family:var(--g-font-head); font-weight:800; flex-shrink:0; position:relative; }

        .g-skill-tags { display:flex; flex-wrap:wrap; gap:.4rem; }
        .g-skill-tag { background:var(--g-soft); color:var(--g-ink); border:1px solid var(--g-border); font-size:.76rem; font-weight:600; padding:.22rem .7rem; border-radius:999px; }
        .g-freelancer-badge { display:inline-flex; align-items:center; gap:.3rem; background:var(--g-yellow); color:var(--g-ink); font-family:var(--g-font-head); font-size:.66rem; font-weight:800; letter-spacing:.06em; text-transform:uppercase; padding:.2rem .6rem; border-radius:999px; border:1px solid var(--g-ink); }

        /* Lencana status */
        .g-status-badge, .g-pay-badge { display:inline-flex; align-items:center; gap:.35rem; padding:.28rem .7rem; border-radius:999px; font-size:.76rem; font-weight:700; border:1px solid transparent; white-space:nowrap; }
        .g-status-menunggu, .g-pay-warn { background:var(--g-yellow); color:var(--g-ink); border-color:var(--g-ink); }
        .g-status-diterima, .g-pay-info { background:var(--g-soft); color:var(--g-ink); border-color:var(--g-border); }
        .g-status-selesai, .g-pay-success { background:var(--g-ok-bg); color:var(--g-ok); border-color:var(--g-ok-bd); }
        .g-status-ditolak, .g-pay-danger { background:var(--g-bad-bg); color:var(--g-bad); border-color:var(--g-bad-bd); }
        .g-pay-primary { background:var(--g-ink); color:#fff; }
        .g-pay-muted { background:var(--g-soft); color:var(--g-muted); border-color:var(--g-border); }

        /* Pagination */
        .pagination { gap:.4rem; justify-content:center; flex-wrap:wrap; }
        .page-link { border:1px solid var(--g-border) !important; border-radius:10px !important; color:var(--g-ink); min-width:40px; text-align:center; font-weight:600; }
        .page-link:hover { background:var(--g-soft); color:var(--g-ink); }
        .page-link:focus { box-shadow:none; }
        .page-item.active .page-link { background:var(--g-ink); border-color:var(--g-ink) !important; color:#fff; }
        .page-item.disabled .page-link { color:#B5B5B5; background:#fff; }

        /* ---------- Carousel dengan panah ---------- */
        .g-carousel { position:relative; max-width:100%; }
        .g-track { display:flex; gap:1rem; overflow:hidden; scroll-behavior:smooth; padding:.5rem .25rem 1rem; }
        .g-track > * { flex:0 0 auto; }
        .g-arrow { position:absolute; top:50%; transform:translateY(-50%); z-index:2; width:42px; height:42px; border-radius:50%; border:1px solid var(--g-ink); background:#fff; color:var(--g-ink); box-shadow:0 3px 0 0 var(--g-ink); display:none; align-items:center; justify-content:center; }
        .g-arrow:hover { background:var(--g-yellow); }
        .g-arrow-prev { left:-6px; }
        .g-arrow-next { right:-6px; }
        .g-carousel.can-prev .g-arrow-prev { display:flex; }
        .g-carousel.can-next .g-arrow-next { display:flex; }

        /* ---------- Hero beranda ---------- */
        .g-hero { position:relative; overflow:hidden; background:var(--g-ink); color:#fff; border-radius:28px; padding:3.5rem 3.5rem 2.5rem; margin:1.5rem 0 3.5rem; border:1px solid #2B2B2B; }
        .g-hero::before { content:""; position:absolute; inset:0; background-image:linear-gradient(#2B2B2B 1px, transparent 1px), linear-gradient(90deg, #2B2B2B 1px, transparent 1px); background-size:48px 48px; opacity:.4; pointer-events:none; }
        .g-hero::after { content:""; position:absolute; width:340px; height:340px; border-radius:50%; border:1.5px dashed var(--g-yellow); right:-70px; top:-100px; opacity:.55; pointer-events:none; }
        .g-hero-inner { position:relative; z-index:1; max-width:780px; }
        .g-hero-eyebrow { display:inline-flex; align-items:center; gap:.5rem; background:#1C1B1B; border:1px solid rgba(255,255,255,.2); padding:.35rem .9rem; border-radius:999px; font-family:var(--g-font-head); font-size:.68rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; margin-bottom:1.25rem; }
        .g-hero-eyebrow i { color:var(--g-yellow); font-size:.95rem; }
        .g-hero h1 { font-size:clamp(2rem,4.6vw,3.5rem); line-height:1.1; letter-spacing:-.03em; margin-bottom:1rem; color:#fff; }
        .g-mark { color:var(--g-yellow); text-decoration:underline; text-decoration-color:rgba(250,204,21,.5); text-decoration-thickness:2px; text-underline-offset:.4rem; }
        .g-hero-sub { color:#C9C6C5; font-size:1.05rem; max-width:620px; margin-bottom:1.75rem; }
        .g-hero-search { display:flex; align-items:center; gap:.5rem; background:#fff; border-radius:14px; padding:.4rem .4rem .4rem 1rem; max-width:640px; }
        .g-hero-search i.lead { color:var(--g-muted); font-size:1.1rem; }
        .g-hero-search input { border:0; flex:1; min-width:0; padding:.7rem .25rem; font-size:1rem; color:var(--g-ink); background:transparent; }
        .g-hero-search input:focus { outline:none; box-shadow:none; }
        .g-hero-search .btn { flex-shrink:0; padding:.7rem 1.4rem; }
        .g-hero-tags { display:flex; flex-wrap:wrap; align-items:center; gap:.5rem; margin-top:1.25rem; }
        .g-hero-tags span { font-size:.78rem; font-weight:600; color:#A3A3A3; margin-right:.25rem; }
        .g-hero-tags a { border:1px solid rgba(255,255,255,.3); color:#D4D4D4; padding:.28rem .85rem; border-radius:999px; font-size:.8rem; font-weight:600; text-decoration:none; transition:.15s; }
        .g-hero-tags a:hover { border-color:var(--g-yellow); color:var(--g-yellow); }
        .g-hero-stats { position:relative; z-index:1; display:grid; grid-template-columns:repeat(3, 1fr); gap:1.5rem; margin-top:2.5rem; padding-top:1.75rem; border-top:1px solid rgba(255,255,255,.15); }
        .g-hero-stat { display:flex; align-items:center; gap:1rem; }
        .g-hero-stat .ico { width:46px; height:46px; border-radius:12px; background:#1C1B1B; border:1px solid rgba(255,255,255,.15); color:var(--g-yellow); display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }
        .g-hero-stat strong { display:block; font-family:var(--g-font-head); font-size:1.5rem; font-weight:800; line-height:1.1; }
        .g-hero-stat small { color:#A3A3A3; font-size:.82rem; }
        @media (max-width: 767.98px) {
            .g-hero { padding:2rem 1.25rem 1.5rem; border-radius:20px; margin:1rem 0 2.5rem; }
            .g-hero-stats { grid-template-columns:1fr; gap:1rem; margin-top:1.75rem; }
            .g-hero-search { padding-left:.75rem; }
            .g-hero-search .btn span { display:none; }
        }

        /* ---------- Kartu kategori ---------- */
        .g-track > .g-cat-card { flex:1 0 200px; min-width:200px; }
        .g-cat-card { display:flex; flex-direction:column; justify-content:space-between; gap:1.5rem; padding:1.25rem; border-radius:16px; background:#fff; border:1px solid var(--g-border); color:var(--g-ink); text-decoration:none; transition:transform .18s, border-color .18s, box-shadow .18s; }
        .g-cat-card .ico { width:46px; height:46px; border-radius:12px; background:var(--g-soft); border:1px solid var(--g-border); display:flex; align-items:center; justify-content:center; font-size:1.35rem; }
        .g-cat-card .name { font-family:var(--g-font-head); font-weight:800; font-size:1.05rem; line-height:1.25; letter-spacing:-.01em; }
        .g-cat-card .foot { display:flex; justify-content:space-between; align-items:center; padding-top:.75rem; border-top:1px solid var(--g-border); font-size:.8rem; font-weight:600; color:var(--g-muted); }
        .g-cat-card:hover { transform:translateY(-2px); border-color:var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); color:var(--g-ink); }
        .g-cat-card:hover .foot i { transform:translateX(3px); }
        .g-cat-card .foot i { transition:transform .15s; }
        .g-cat-card.active { border:2px solid var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); }
        .g-cat-card.active .ico { background:var(--g-yellow); border-color:var(--g-ink); }
        .g-cat-card.active .foot { color:var(--g-ink); }

        /* ---------- Jasa populer (blok hitam) ---------- */
        .g-dark { background:var(--g-ink); color:#fff; border-radius:28px; padding:3rem; margin:4rem 0; border:1px solid #2B2B2B; }
        .g-dark-head { display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem; }
        .g-dark .g-kicker { color:var(--g-yellow); }
        .g-dark p.sub { color:#A3A3A3; margin:.4rem 0 0; max-width:560px; }
        .g-pop { width:270px; display:flex; flex-direction:column; gap:1.1rem; padding:1.1rem; border-radius:18px; background:#1A1C1C; border:1px solid #333; color:#fff; text-decoration:none; transition:border-color .18s, transform .18s; }
        .g-pop:hover { border-color:var(--g-yellow); transform:translateY(-2px); color:#fff; }
        .g-pop-media { height:150px; border-radius:12px; background:#1C1B1B; border:1px solid rgba(255,255,255,.12); display:flex; align-items:center; justify-content:center; overflow:hidden; }
        .g-pop-media > i { font-size:3rem; color:var(--g-yellow); }
        .g-pop .tag { font-family:var(--g-font-head); font-size:.68rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:var(--g-yellow); }
        .g-pop .ttl { font-family:var(--g-font-head); font-weight:800; font-size:1.2rem; line-height:1.25; letter-spacing:-.01em; margin-top:.2rem; }
        .g-pop .go { margin-top:auto; padding-top:.9rem; border-top:1px solid rgba(255,255,255,.12); display:flex; justify-content:space-between; align-items:center; font-size:.85rem; font-weight:600; color:#C9C6C5; }
        .g-pop:hover .go { color:var(--g-yellow); }
        .g-dark .g-arrow { border-color:var(--g-yellow); box-shadow:0 3px 0 0 var(--g-yellow); }

        /* Ilustrasi mockup di kartu populer */
        .g-mock { width:100%; height:100%; display:flex; align-items:center; justify-content:center; }
        .m-swatches { display:flex; gap:.5rem; }
        .m-swatch { width:34px; height:34px; border-radius:9px; border:1px solid var(--g-ink); }
        .m-photo { width:62%; aspect-ratio:4/3; border:1px solid rgba(255,255,255,.35); border-radius:8px; display:flex; align-items:center; justify-content:center; color:var(--g-yellow); font-size:1.8rem; background:#0A0A0A; }
        .m-slide { width:70%; height:96px; border:1px solid rgba(255,255,255,.35); border-radius:8px; padding:.6rem; display:flex; flex-direction:column; justify-content:space-between; background:#0A0A0A; }
        .m-bar { height:7px; border-radius:3px; background:rgba(255,255,255,.3); }
        .m-bar.y { background:var(--g-yellow); width:45%; }
        .m-browser { width:78%; border:1px solid rgba(255,255,255,.35); border-radius:8px; overflow:hidden; background:#0A0A0A; }
        .m-browser .top { display:flex; gap:4px; padding:.4rem .5rem; border-bottom:1px solid rgba(255,255,255,.2); }
        .m-browser .top span { width:7px; height:7px; border-radius:50%; background:rgba(255,255,255,.4); }
        .m-browser .top span:first-child { background:var(--g-yellow); }
        .m-browser .body { padding:.6rem; display:flex; flex-direction:column; gap:6px; }
        .m-phone { width:84px; height:112px; border:1px solid rgba(255,255,255,.35); border-radius:12px; background:#0A0A0A; position:relative; display:flex; align-items:center; justify-content:center; color:var(--g-yellow); font-size:2rem; }
        .m-phone .tl { position:absolute; left:10px; right:10px; bottom:10px; height:5px; border-radius:3px; background:rgba(255,255,255,.25); overflow:hidden; }
        .m-phone .tl span { display:block; height:100%; width:55%; background:var(--g-yellow); }
        @media (max-width: 767.98px) { .g-dark { padding:1.75rem 1.25rem; border-radius:20px; margin:2.5rem 0; } .g-pop { width:240px; } .g-track > .g-cat-card { flex-basis:160px; min-width:160px; } }

        /* ---------- Keunggulan ---------- */
        .g-features { margin:4rem 0; }
        .g-features-head { text-align:center; max-width:640px; margin:0 auto 2rem; }
        .g-features-head p { color:var(--g-muted); margin:.5rem 0 0; }
        .g-feature { height:100%; background:#fff; border:1px solid var(--g-border); border-radius:16px; padding:1.5rem; transition:transform .18s, border-color .18s, box-shadow .18s; }
        .g-feature:hover { transform:translateY(-2px); border-color:var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); }
        .g-feature-icon { position:relative; width:48px; height:48px; border-radius:12px; background:var(--g-soft); border:1px solid var(--g-border); display:flex; align-items:center; justify-content:center; font-size:1.3rem; margin-bottom:1.1rem; }
        .g-feature-icon::after { content:""; position:absolute; top:7px; right:7px; width:8px; height:8px; border-radius:50%; background:var(--g-yellow); }
        .g-feature h3 { font-size:1.1rem; margin-bottom:.4rem; }
        .g-feature p { margin:0; color:var(--g-muted); font-size:.92rem; }

        /* ---------- Banner ajakan ---------- */
        .g-cta { position:relative; overflow:hidden; background:var(--g-ink); color:#fff; border-radius:24px; padding:3rem; margin:4rem 0 1rem; border:1px solid var(--g-ink); display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:2rem; }
        .g-cta::after { content:""; position:absolute; right:-60px; top:50%; transform:translateY(-50%); width:320px; height:320px; border-radius:50%; border:1.5px dashed rgba(250,204,21,.45); box-shadow:0 0 0 55px transparent, 0 0 0 56px rgba(255,255,255,.1); pointer-events:none; }
        .g-cta > * { position:relative; z-index:1; }
        .g-cta h2 { font-size:clamp(1.6rem,3vw,2.5rem); line-height:1.2; margin:.75rem 0; max-width:680px; color:#fff; }
        .g-cta p { color:#C9C6C5; margin:0; max-width:560px; }
        .g-cta .pill { display:inline-flex; align-items:center; gap:.5rem; background:#1C1B1B; border:1px solid rgba(255,255,255,.2); color:var(--g-yellow); border-radius:999px; padding:.3rem .85rem; font-family:var(--g-font-head); font-size:.68rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
        .g-cta .pill::before { content:""; width:8px; height:8px; border-radius:50%; background:var(--g-yellow); }
        .g-cta .btn-accent { padding:1rem 1.75rem; border-radius:12px; box-shadow:0 4px 0 0 #fff; }
        @media (max-width: 767.98px) { .g-cta { padding:1.75rem 1.25rem; } }

        /* ---------- Kartu freelancer (hasil pencarian) ---------- */
        .g-fcard { position:relative; display:flex; flex-direction:column; gap:1rem; height:100%; background:#fff; border:1px solid var(--g-border); border-radius:16px; padding:1.5rem; text-decoration:none; color:var(--g-ink); transition:transform .18s, border-color .18s, box-shadow .18s; }
        .g-fcard:hover { transform:translateY(-2px); border-color:var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); color:var(--g-ink); }
        .g-fcard-head { display:flex; gap:.9rem; align-items:flex-start; }
        .g-fcard .ttl { font-family:var(--g-font-head); font-weight:800; font-size:1.1rem; line-height:1.3; letter-spacing:-.01em; margin:0; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .g-fcard .ttl a { color:var(--g-ink); text-decoration:none; }
        .g-fcard .nm { font-family:var(--g-font-head); font-weight:700; font-size:.95rem; display:flex; align-items:center; gap:.35rem; flex-wrap:wrap; }
        .g-fcard .nm i { font-size:.95rem; }
        .g-fcard a.owner { position:relative; z-index:2; color:var(--g-ink); text-decoration:none; }
        .g-fcard a.owner:hover { text-decoration:underline; text-decoration-color:var(--g-yellow); text-decoration-thickness:2px; }
        .g-fcard .meta { font-size:.82rem; color:var(--g-muted); }
        .g-fcard .bio { margin:0; font-size:.9rem; color:#404040; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; min-height:2.9em; }
        .g-fcard .price small { display:block; color:var(--g-muted); font-size:.75rem; }
        .g-fcard .price strong { font-family:var(--g-font-head); font-size:1.15rem; font-weight:800; }
        .g-fcard .foot { margin-top:auto; padding-top:1rem; border-top:1px solid var(--g-border); display:flex; justify-content:space-between; align-items:center; gap:.5rem; flex-wrap:wrap; font-size:.82rem; color:var(--g-muted); }
        .g-fcard .foot strong { color:var(--g-ink); }
        .g-fcard .go { background:var(--g-ink); color:#fff; font-size:.82rem; font-weight:600; padding:.5rem 1rem; border-radius:10px; }
        .g-fcard:hover .go { background:#262626; }

        .g-filter { display:flex; align-items:center; flex-wrap:wrap; gap:.5rem; background:#fff; border:1px solid var(--g-border); border-radius:14px; padding:.5rem; }
        .g-filter .rp { padding:0 .25rem 0 .6rem; color:var(--g-muted); font-weight:600; font-size:.85rem; }
        .g-filter input { border:0; width:150px; max-width:100%; background:var(--g-soft); border-radius:10px; padding:.55rem .75rem; }
        .g-filter input:focus { outline:none; box-shadow:0 0 0 1px var(--g-ink); }
        .g-filter a.reset { font-size:.82rem; font-weight:600; color:var(--g-ink); padding:0 .5rem; }

        /* ---------- Profil freelancer & detail jasa ---------- */
        .g-profile-head { padding:1.75rem; position:relative; }
        .g-profile-head::after { content:""; position:absolute; right:0; top:0; width:140px; height:90px; background:var(--g-yellow-soft); border-bottom-left-radius:90px; pointer-events:none; }
        .g-profile-head > * { position:relative; z-index:1; }
        .g-profile-head .zap { position:absolute; right:-2px; bottom:-2px; width:26px; height:26px; border-radius:50%; background:var(--g-yellow); border:2px solid #fff; color:var(--g-ink); display:flex; align-items:center; justify-content:center; font-size:.75rem; }
        .g-fm-tabs { background:#fff; border:1px solid var(--g-border); border-radius:14px; padding:.35rem; display:flex; gap:.25rem; border-bottom:1px solid var(--g-border); }
        .g-fm-tabs .nav-item { flex:1; }
        .g-fm-tabs .nav-link { width:100%; color:var(--g-muted); font-weight:600; font-size:.9rem; border:0; border-radius:10px; padding:.65rem .5rem; background:none; display:flex; justify-content:center; align-items:center; gap:.4rem; }
        .g-fm-tabs .nav-link:hover { color:var(--g-ink); background:var(--g-soft); }
        .g-fm-tabs .nav-link.active { color:#fff; background:var(--g-ink); }
        .g-fm-tabs .count { background:var(--g-yellow); color:var(--g-ink); border-radius:999px; font-size:.7rem; font-weight:800; padding:0 .45rem; }
        .g-fm-service { display:flex; justify-content:space-between; align-items:center; gap:1rem; flex-wrap:wrap; transition:border-color .18s, box-shadow .18s; }
        .g-fm-service:hover { border-color:var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); }
        .g-info-tile { background:var(--g-soft); border:1px solid var(--g-border); border-radius:12px; padding:1rem; height:100%; }
        .g-info-tile .ico { width:38px; height:38px; border-radius:10px; background:var(--g-ink); color:var(--g-yellow); display:flex; align-items:center; justify-content:center; margin-bottom:.6rem; }
        .g-info-tile strong { display:block; font-family:var(--g-font-head); font-size:.92rem; }
        .g-info-tile span { font-size:.82rem; color:var(--g-muted); }
        .g-side-list { list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:.75rem; font-size:.88rem; }
        .g-side-list li { display:flex; gap:.6rem; align-items:flex-start; }
        .g-side-list i { margin-top:.15rem; }
        .g-kv { background:var(--g-soft); border:1px solid var(--g-border); border-radius:12px; padding:.25rem 1rem; }
        .g-kv div { display:flex; justify-content:space-between; gap:1rem; padding:.6rem 0; font-size:.88rem; border-bottom:1px solid var(--g-border); }
        .g-kv div:last-child { border-bottom:0; }
        .g-kv span { color:var(--g-muted); }
        .g-kv strong { text-align:right; }

        .g-price-card { position:sticky; top:96px; }
        .g-price-card .amount { font-family:var(--g-font-head); font-size:2rem; font-weight:800; letter-spacing:-.02em; line-height:1.1; }
        .g-steps { counter-reset:step; display:grid; grid-template-columns:repeat(3, 1fr); gap:1.25rem; }
        .g-step .no { width:36px; height:36px; border-radius:50%; background:var(--g-ink); color:#fff; display:flex; align-items:center; justify-content:center; font-family:var(--g-font-head); font-weight:800; margin-bottom:.75rem; }
        .g-step:last-child .no { background:var(--g-yellow); color:var(--g-ink); border:1px solid var(--g-ink); }
        .g-step h6 { font-size:.98rem; margin-bottom:.25rem; }
        .g-step p { margin:0; font-size:.86rem; color:var(--g-muted); }
        @media (max-width: 767.98px) { .g-steps { grid-template-columns:1fr; } .g-price-card { position:static; } }

        .g-svc-card { display:flex; flex-direction:column; height:100%; background:#fff; border:1px solid var(--g-border); border-radius:16px; overflow:hidden; text-decoration:none; color:var(--g-ink); transition:transform .18s, border-color .18s, box-shadow .18s; }
        .g-svc-card:hover { transform:translateY(-2px); border-color:var(--g-ink); box-shadow:0 4px 0 0 var(--g-ink); color:var(--g-ink); }
        .g-svc-card .img { aspect-ratio:16/10; background:var(--g-soft); border-bottom:1px solid var(--g-border); display:flex; align-items:center; justify-content:center; font-size:2.2rem; color:var(--g-ink); overflow:hidden; }
        .g-svc-card .img img { width:100%; height:100%; object-fit:cover; }
        .g-svc-card .bd { padding:1rem 1.1rem 1.1rem; display:flex; flex-direction:column; gap:.4rem; flex:1; }
        .g-svc-card .tg { align-self:flex-start; font-size:.68rem; font-weight:800; font-family:var(--g-font-head); text-transform:uppercase; letter-spacing:.06em; background:var(--g-ink); color:#fff; padding:.15rem .55rem; border-radius:6px; }
        .g-svc-card .tt { font-family:var(--g-font-head); font-weight:800; font-size:.98rem; line-height:1.3; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .g-svc-card .by { font-size:.8rem; color:var(--g-muted); }
        .g-svc-card .pr { margin-top:auto; font-family:var(--g-font-head); font-weight:800; }

        /* ---------- Pembayaran ---------- */
        .g-pay-option { display:flex; align-items:center; gap:.9rem; border:1px solid var(--g-border); border-radius:14px; padding:.95rem 1.1rem; cursor:pointer; transition:border-color .15s, box-shadow .15s; margin:0; }
        .g-pay-option:hover { border-color:var(--g-ink); }
        .g-pay-option:has(input:checked) { border:2px solid var(--g-ink); box-shadow:0 3px 0 0 var(--g-ink); padding:calc(.95rem - 1px) calc(1.1rem - 1px); }
        .g-pay-option input { width:20px; height:20px; accent-color:#0A0A0A; flex-shrink:0; }
        .g-pay-icon { width:40px; height:40px; border-radius:10px; background:var(--g-soft); border:1px solid var(--g-border); color:var(--g-ink); display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:1.1rem; }
        .g-pay-option:has(input:checked) .g-pay-icon { background:var(--g-yellow); border-color:var(--g-ink); }
        .g-drop { background:var(--g-soft); border:1.5px dashed #A3A3A3; border-radius:14px; padding:1.5rem; text-align:center; }
        .g-drop .ico { width:48px; height:48px; border-radius:50%; background:var(--g-yellow); border:1px solid var(--g-ink); display:inline-flex; align-items:center; justify-content:center; font-size:1.3rem; margin-bottom:.6rem; }
        .g-drop input[type=file] { background:#fff; }
        .g-total { background:var(--g-ink); color:#fff; border-radius:14px; padding:1.25rem; display:flex; justify-content:space-between; align-items:center; gap:1rem; }
        .g-total small { color:#A3A3A3; display:block; font-weight:600; }
        .g-total strong { font-family:var(--g-font-head); font-size:1.8rem; font-weight:800; letter-spacing:-.02em; line-height:1.1; }
        .g-total .ico { width:44px; height:44px; border-radius:50%; background:var(--g-yellow); color:var(--g-ink); display:flex; align-items:center; justify-content:center; font-size:1.2rem; flex-shrink:0; }

        /* ---------- Footer ---------- */
        .g-footer { background:var(--g-ink); color:#fff; margin-top:4rem; }
        .g-footer h6 { font-family:var(--g-font-head); color:#fff; font-weight:800; font-size:1rem; margin-bottom:1rem; letter-spacing:-.01em; }
        .g-footer a.fl { color:#B5B3B3; text-decoration:none; font-size:.88rem; display:block; margin-bottom:.55rem; transition:color .15s; }
        .g-footer a.fl:hover { color:var(--g-yellow); }
        .g-footer-bottom { border-top:1px solid #1C1B1B; padding-top:1.5rem; padding-bottom:1.5rem; font-size:.84rem; color:#B5B3B3; }
        .g-footer-social { width:36px; height:36px; border-radius:50%; border:1px solid #2B2B2B; display:flex; align-items:center; justify-content:center; color:#B5B3B3; text-decoration:none; flex-shrink:0; transition:.15s; }
        .g-footer-social:hover { border-color:var(--g-yellow); color:var(--g-yellow); }

        /* ---------- Modal masuk / daftar ---------- */
        .g-fm-content { border-radius:18px; overflow:hidden; border:1px solid var(--g-ink); }
        .g-fm-header { background:var(--g-ink); border:0; padding:1.1rem 1.4rem; align-items:center; gap:.85rem; }
        .g-fm-header .ico { width:40px; height:40px; border-radius:10px; background:#1C1B1B; border:1px solid rgba(255,255,255,.2); color:var(--g-yellow); display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
        .g-fm-header h5 { color:#fff; margin:0; font-size:1.1rem; }
        .g-fm-header small { color:#A3A3A3; font-size:.78rem; }
        .g-fm-body { padding:1.5rem; }
        .g-pass { position:relative; }
        .g-pass .form-control { padding-right:2.75rem; }
        .g-pass button { position:absolute; right:.35rem; top:50%; transform:translateY(-50%); border:0; background:none; color:var(--g-muted); width:36px; height:36px; border-radius:8px; }
        .g-pass button:hover { color:var(--g-ink); background:var(--g-soft); }
        .g-link-u { color:var(--g-ink); font-weight:700; text-decoration:underline; text-decoration-color:var(--g-yellow); text-decoration-thickness:2px; text-underline-offset:4px; }

        @media (prefers-reduced-motion: reduce) { * { transition:none !important; scroll-behavior:auto !important; } }

        /* ---------- Avatar (foto / inisial) ---------- */
        .g-ava-img { position:absolute; inset:0; width:100%; height:100%; border-radius:50%; object-fit:cover; }

        /* ---------- Form dalam panel (dibatasi lebarnya) ---------- */
        .g-form-panel { max-width:720px; }
        .g-form-actions { display:flex; align-items:center; gap:.75rem; flex-wrap:wrap; padding-top:1.25rem; margin-top:.5rem; border-top:1px solid var(--g-border); }

        /* ---------- Chat ---------- */
        .g-conv { display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid var(--g-border); text-decoration:none; color:var(--g-ink); transition:background .15s; }
        .g-conv:last-child { border-bottom:0; }
        .g-conv:hover { background:#FAFAFA; color:var(--g-ink); }
        .g-conv .body { flex:1; min-width:0; }
        .g-conv .nm { font-family:var(--g-font-head); font-weight:700; }
        .g-conv.unread .nm { font-weight:800; }
        .g-conv .pv { color:var(--g-muted); font-size:.88rem; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .g-conv.unread .pv { color:var(--g-ink); font-weight:600; }
        .g-conv .when { font-size:.76rem; color:var(--g-muted); white-space:nowrap; }
        .g-count { min-width:22px; height:22px; padding:0 6px; border-radius:999px; background:var(--g-yellow); border:1px solid var(--g-ink); font-family:var(--g-font-head); font-size:.72rem; font-weight:800; display:inline-flex; align-items:center; justify-content:center; }

        .g-chat { display:flex; flex-direction:column; height:clamp(420px, 70vh, 680px); }
        .g-chat-head { display:flex; align-items:center; gap:.85rem; padding:.9rem 1.25rem; border-bottom:1px solid var(--g-border); flex-shrink:0; }
        .g-chat-body { flex:1; min-height:0; overflow-y:auto; padding:1.25rem; background:var(--g-soft); display:flex; flex-direction:column; gap:.5rem; }
        .g-chat-empty { margin:auto; text-align:center; color:var(--g-muted); font-size:.9rem; max-width:280px; }
        .g-msg { max-width:min(80%, 520px); padding:.55rem .85rem; border-radius:14px; font-size:.92rem; line-height:1.45; white-space:pre-wrap; overflow-wrap:anywhere; }
        .g-msg.them { align-self:flex-start; background:#fff; border:1px solid var(--g-border); border-bottom-left-radius:4px; }
        .g-msg.me { align-self:flex-end; background:var(--g-ink); color:#fff; border-bottom-right-radius:4px; }
        .g-msg .t { display:block; font-size:.68rem; opacity:.6; margin-top:.2rem; text-align:right; }
        .g-chat-form { display:flex; align-items:flex-end; gap:.5rem; padding:.85rem; border-top:1px solid var(--g-border); flex-shrink:0; }
        .g-chat-form textarea { resize:none; max-height:140px; }
        .g-chat-form .btn { flex-shrink:0; }

        /* ---------- Notifikasi ---------- */
        .g-notif { display:flex; align-items:flex-start; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid var(--g-border); text-decoration:none; color:var(--g-ink); transition:background .15s; }
        .g-notif:last-child { border-bottom:0; }
        .g-notif:hover { background:#FAFAFA; color:var(--g-ink); }
        .g-notif.unread { background:var(--g-yellow-soft); }
        .g-notif.unread:hover { background:#FEF3A6; }
        .g-notif .ico { width:40px; height:40px; border-radius:10px; background:var(--g-ink); color:var(--g-yellow); display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .g-notif .body { flex:1; min-width:0; }
        .g-notif .ttl { font-family:var(--g-font-head); font-weight:800; font-size:.95rem; }
        .g-notif .msg { font-size:.88rem; color:#404040; overflow-wrap:anywhere; }
        .g-notif .when { font-size:.76rem; color:var(--g-muted); white-space:nowrap; }

        /* ---------- Leaderboard ---------- */
        .g-lb-row { display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-bottom:1px solid var(--g-border); }
        .g-lb-row:last-child { border-bottom:0; }
        .g-lb-row .who { flex:1; min-width:0; }
        .g-lb-row .who a { font-family:var(--g-font-head); font-weight:800; color:var(--g-ink); text-decoration:none; }
        .g-lb-row .who a:hover { text-decoration:underline; text-decoration-color:var(--g-yellow); text-decoration-thickness:2px; }
        .g-lb-row .sub { font-size:.82rem; color:var(--g-muted); overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .g-lb-row .score { text-align:right; white-space:nowrap; }
        .g-lb-row .score strong { display:block; font-family:var(--g-font-head); font-size:1.25rem; font-weight:800; line-height:1.1; }
        .g-lb-row .score small { font-size:.74rem; color:var(--g-muted); }
        .g-rank { width:40px; height:40px; border-radius:50%; background:var(--g-soft); border:1px solid var(--g-border); display:flex; align-items:center; justify-content:center; font-family:var(--g-font-head); font-weight:800; flex-shrink:0; }
        .g-rank.top { background:var(--g-yellow); border-color:var(--g-ink); }
    </style>
    @stack('styles')
</head>
<body>

@php
    $navUser = auth()->user();
    $navUnreadNotifications = $navUnreadNotifications ?? 0;
    $navUnreadMessages = $navUnreadMessages ?? 0;
    $footerCategories = (isset($categories) && $categories instanceof \Illuminate\Support\Collection)
        ? $categories
        : \App\Models\Category::orderBy('name')->get();
@endphp

<header class="g-nav">
    <nav class="navbar navbar-expand-lg">
        <div class="container g-container">
            <a class="g-brand" href="{{ route('home') }}">
                <span class="g-brand-icon"><i class="bi bi-briefcase-fill"></i></span>Gawean
            </a>

            {{-- Ikon chat & notifikasi untuk layar kecil (di luar menu yang bisa dilipat) --}}
            @auth
                <div class="g-nav-icons d-flex d-lg-none ms-auto me-2">
                    @include('layouts._nav-icons')
                </div>
            @endauth

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#gNavMenu"
                    aria-controls="gNavMenu" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="gNavMenu">
                <div class="g-nav-links mx-lg-auto mt-3 mt-lg-0">
                    <a href="{{ route('home') }}" class="g-link {{ request()->routeIs('home', 'services.show', 'freelancers.show', 'clients.show') ? 'active' : '' }}">Jelajah Jasa</a>
                    <a href="{{ route('leaderboard') }}" class="g-link {{ request()->routeIs('leaderboard') ? 'active' : '' }}">Leaderboard</a>
                    @auth
                        @if($navUser->is_freelancer)
                            <a href="{{ route('services.mine') }}" class="g-link {{ request()->routeIs('services.mine', 'services.create', 'services.edit') ? 'active' : '' }}">Jasa Saya</a>
                            <a href="{{ route('orders.incoming') }}" class="g-link {{ request()->routeIs('orders.incoming') ? 'active' : '' }}">Pesanan Masuk</a>
                        @endif
                        <a href="{{ route('orders.mine') }}" class="g-link {{ request()->routeIs('orders.mine', 'orders.pay') ? 'active' : '' }}">Pesanan Saya</a>
                    @else
                        <a href="{{ route('home') }}#keunggulan" class="g-link">Cara Kerja</a>
                        <button type="button" class="g-link" data-bs-toggle="modal" data-bs-target="#registerModal">Tawarkan Jasa</button>
                        <button type="button" class="g-link" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk</button>
                    @endauth
                </div>

                <div class="g-nav-actions">
                    @auth
                        <div class="g-nav-icons d-none d-lg-flex">
                            @include('layouts._nav-icons')
                        </div>
                        <a href="{{ route('profile.edit') }}" class="g-nav-user" title="Profil saya">
                            <x-avatar :user="$navUser" :size="26" /><span class="nm">{{ $navUser->name }}</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-outline-primary btn-sm btn-pill px-3">Logout</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-primary btn-pill px-4 g-btn-join" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar</button>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<main class="g-main container g-container py-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

<footer class="g-footer">
    <div class="container g-container pt-5 pb-4">
        <div class="row g-4">
            <div class="col-6 col-lg-3">
                <h6>Kategori Jasa</h6>
                @foreach($footerCategories->take(5) as $footerCategory)
                    <a class="fl" href="{{ route('home', ['category' => $footerCategory->id]) }}">{{ $footerCategory->name }}</a>
                @endforeach
                @if($footerCategories->isEmpty())
                    <a class="fl" href="{{ route('home') }}">Lihat Semua Jasa</a>
                @endif
            </div>
            <div class="col-6 col-lg-3">
                <h6>Untuk Klien</h6>
                <a class="fl" href="{{ route('home') }}#keunggulan">Cara Kerja Gawean</a>
                <a class="fl" href="{{ route('home') }}">Pusat Bantuan</a>
                <a class="fl" href="{{ route('home') }}#keunggulan">Keamanan Transaksi</a>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Untuk Freelancer</h6>
                <a class="fl" href="#" data-bs-toggle="modal" data-bs-target="#registerModal">Mulai Menawarkan Jasa</a>
                <a class="fl" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk sebagai Freelancer</a>
                <a class="fl" href="{{ route('home') }}">Tips Membuat Jasa</a>
            </div>
            <div class="col-6 col-lg-3">
                <h6>Gawean</h6>
                <a class="fl" href="{{ route('home') }}">Tentang Kami</a>
                <a class="fl" href="{{ route('home') }}">Syarat &amp; Ketentuan</a>
                <a class="fl" href="{{ route('home') }}">Kebijakan Privasi</a>
            </div>
        </div>
    </div>
    <div class="container g-container g-footer-bottom d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 text-center text-sm-start">
        <span>&copy; {{ date('Y') }} Gawean &mdash; Jasa Freelance Mahasiswa Indonesia</span>
        <div class="d-flex align-items-center gap-2">
            <a href="#" class="g-footer-social" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
            <a href="#" class="g-footer-social" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
            <a href="#" class="g-footer-social" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
        </div>
    </div>
</footer>

{{-- Modal Masuk --}}
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content g-fm-content">
            <div class="modal-header g-fm-header">
                <span class="ico"><i class="bi bi-briefcase-fill"></i></span>
                <div class="me-auto">
                    <h5 class="modal-title">Masuk ke Gawean</h5>
                    <small>Portal akun mahasiswa &amp; klien</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body g-fm-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="form" value="login">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('form') === 'login' ? old('email') : '' }}"
                               class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="g-pass">
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password">
                            <button type="button" data-toggle-pass aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberModal">
                        <label class="form-check-label" for="rememberModal">Ingat saya</label>
                    </div>
                    <button class="btn btn-primary w-100 py-2">Masuk ke Akun</button>
                    <p class="text-center small text-muted mt-3 mb-0">
                        Belum punya akun?
                        <a href="#" class="g-link-u" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">Daftar di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Daftar --}}
<div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content g-fm-content">
            <div class="modal-header g-fm-header">
                <span class="ico"><i class="bi bi-mortarboard-fill"></i></span>
                <div class="me-auto">
                    <h5 class="modal-title">Daftar Akun Gawean</h5>
                    <small>Gabung sebagai klien atau freelancer</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body g-fm-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="form" value="register">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" value="{{ old('form') === 'register' ? old('name') : '' }}"
                               class="form-control @error('name') is-invalid @enderror" placeholder="Nama lengkap">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('form') === 'register' ? old('email') : '' }}"
                               class="form-control @error('email') is-invalid @enderror" placeholder="nama@email.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="g-pass">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                            <button type="button" data-toggle-pass aria-label="Tampilkan password"><i class="bi bi-eye"></i></button>
                        </div>
                        @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password">
                    </div>
                    <div class="mb-4">
                        <label class="form-label d-block">Saya ingin menjadi:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="client" id="roleClientModal"
                                   @checked(in_array('client', old('form') === 'register' ? old('roles', ['client']) : ['client']))>
                            <label class="form-check-label" for="roleClientModal">Client (pencari jasa)</label>
                        </div>
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" name="roles[]" value="freelancer" id="roleFreelancerModal"
                                   @checked(in_array('freelancer', old('form') === 'register' ? old('roles', []) : []))>
                            <label class="form-check-label" for="roleFreelancerModal">Freelancer (penyedia jasa)</label>
                        </div>
                        @error('roles')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <button class="btn btn-primary w-100 py-2">Daftar</button>
                    <p class="text-center small text-muted mt-3 mb-0">
                        Sudah punya akun?
                        <a href="#" class="g-link-u" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Masuk di sini</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Panah kiri/kanan untuk semua elemen .g-carousel
    document.querySelectorAll('.g-carousel').forEach(function (box) {
        var track = box.querySelector('.g-track');

        function update() {
            var max = track.scrollWidth - track.clientWidth;
            box.classList.toggle('can-prev', track.scrollLeft > 2);
            box.classList.toggle('can-next', track.scrollLeft < max - 2);
        }

        box.querySelector('.g-arrow-prev').addEventListener('click', function () {
            track.scrollBy({ left: -track.clientWidth * 0.8, behavior: 'smooth' });
        });
        box.querySelector('.g-arrow-next').addEventListener('click', function () {
            track.scrollBy({ left: track.clientWidth * 0.8, behavior: 'smooth' });
        });

        track.addEventListener('scroll', update);
        window.addEventListener('resize', update);
        update();
    });

    // Tombol mata untuk menampilkan / menyembunyikan password
    document.querySelectorAll('[data-toggle-pass]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = btn.parentElement.querySelector('input');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.querySelector('i').className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    });
</script>

{{-- Buka lagi modal login/daftar otomatis kalau ada error validasi dari modal itu --}}
@if(old('form') === 'login')
<script>document.addEventListener('DOMContentLoaded', function () { new bootstrap.Modal(document.getElementById('loginModal')).show(); });</script>
@endif
@if(old('form') === 'register')
<script>document.addEventListener('DOMContentLoaded', function () { new bootstrap.Modal(document.getElementById('registerModal')).show(); });</script>
@endif
@auth
<script>
    // Perbarui angka badge chat & notifikasi di navbar tiap 20 detik (polling JSON)
    (function () {
        var url = @json(route('badges'));
        function setBadge(name, n) {
            document.querySelectorAll('[data-badge="' + name + '"]').forEach(function (el) {
                el.textContent = n > 99 ? '99+' : String(n);
                el.hidden = n < 1;
            });
        }
        window.gaweanSetBadge = setBadge;
        function tick() {
            if (document.hidden) return;
            fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (d) { if (d) { setBadge('messages', d.messages); setBadge('notifications', d.notifications); } })
                .catch(function () {});
        }
        window.gaweanRefreshBadges = tick;
        setInterval(tick, 20000);
    })();
</script>
@php
    $firebaseConfig = [
        'apiKey' => config('services.firebase.api_key'),
        'authDomain' => config('services.firebase.auth_domain'),
        'projectId' => config('services.firebase.project_id'),
        'storageBucket' => config('services.firebase.storage_bucket'),
        'messagingSenderId' => config('services.firebase.messaging_sender_id'),
        'appId' => config('services.firebase.app_id'),
    ];
    $meId = auth()->id();
@endphp
@if($firebaseConfig['apiKey'] && $firebaseConfig['projectId'])
<script type="module">
    // Firebase Firestore sebagai pemicu realtime. Isi pesan TIDAK disimpan di sini, hanya id pesan terakhir.
    import { initializeApp } from 'https://www.gstatic.com/firebasejs/11.0.2/firebase-app.js';
    import { getFirestore, doc, onSnapshot, setDoc, serverTimestamp } from 'https://www.gstatic.com/firebasejs/11.0.2/firebase-firestore.js';

    const app = initializeApp(@json($firebaseConfig));
    const db = getFirestore(app);
    window.gaweanFirebase = { db, doc, onSnapshot, setDoc, serverTimestamp };

    // Ada pesan baru untuk saya -> segarkan badge navbar saat itu juga
    onSnapshot(doc(db, 'userSignals', 'u_' + @json($meId)), function (snap) {
        if (snap.exists() && window.gaweanRefreshBadges) window.gaweanRefreshBadges();
    }, function (err) {
        console.warn('Firebase realtime tidak aktif, badge memakai polling:', err.code);
    });
</script>
@endif
@endauth
@stack('scripts')
</body>
</html>