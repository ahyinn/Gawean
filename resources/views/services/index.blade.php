@extends('layouts.app')
@section('title', 'Gawean - Jasa Freelance Mahasiswa')

@section('content')
@php
    $isSearching = request()->anyFilled(['q', 'category', 'max_price']);
@endphp

{{-- ===================== BERANDA POLOS ===================== --}}
@unless($isSearching)

    {{-- Hero --}}
    <section class="g-hero">
        <div class="g-hero-inner">
            <span class="g-hero-eyebrow"><i class="bi bi-mortarboard-fill"></i> Dibuat oleh &amp; untuk mahasiswa</span>
            <h1>Butuh jasa? Serahkan pada <span class="g-mark">freelancer mahasiswa</span> terbaik.</h1>
            <p class="g-hero-sub">Desain, video, foto, presentasi, sampai website — temukan jasa berkualitas dengan harga bersahabat, langsung dari mahasiswa di kampusmu.</p>

            <form method="GET" action="{{ route('home') }}" class="g-hero-search">
                <i class="bi bi-search lead"></i>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari jasa: desain, video, coding..." aria-label="Cari jasa">
                <button type="submit" class="btn btn-accent"><span>Cari</span> <i class="bi bi-arrow-right"></i></button>
            </form>

            <div class="g-hero-tags">
                <span>Rekomendasi:</span>
                @foreach($categories->take(5) as $category)
                    <a href="{{ route('home', ['category' => $category->id]) }}">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>

        @php
            // Angka asli dari database (bukan angka karangan)
            $statFreelancers = \App\Models\User::where('is_freelancer', true)->count();
            $statServices = \App\Models\Service::count();
        @endphp
        <div class="g-hero-stats">
            <div class="g-hero-stat">
                <span class="ico"><i class="bi bi-people-fill"></i></span>
                <div><strong>{{ number_format($statFreelancers, 0, ',', '.') }}</strong><small>Freelancer mahasiswa</small></div>
            </div>
            <div class="g-hero-stat">
                <span class="ico"><i class="bi bi-briefcase-fill"></i></span>
                <div><strong>{{ number_format($statServices, 0, ',', '.') }}</strong><small>Jasa tersedia</small></div>
            </div>
            <div class="g-hero-stat">
                <span class="ico"><i class="bi bi-grid-fill"></i></span>
                <div><strong>{{ $categories->count() }}</strong><small>Kategori jasa</small></div>
            </div>
        </div>
    </section>

@endunless

{{-- ===================== KARTU KATEGORI ===================== --}}
@if($isSearching)
    <div class="g-crumbs mb-3">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="bi bi-chevron-right small"></i>
        <span class="cur">
            @php $crumbCategory = $categories->firstWhere('id', (int) request('category')); @endphp
            {{ $crumbCategory ? $crumbCategory->name : 'Hasil pencarian' }}
        </span>
    </div>
@else
    <div class="mb-2">
        <span class="g-kicker">Kategori Pilihan</span>
        <h2 class="g-section-title">Eksplorasi ragam keahlian mahasiswa bertalenta</h2>
    </div>
@endif

<div class="g-carousel">
    <button type="button" class="g-arrow g-arrow-prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
    <div class="g-track">
        @foreach($categories as $category)
            <a href="{{ route('home', ['category' => $category->id]) }}"
               class="g-cat-card {{ request('category') == $category->id ? 'active' : '' }}">
                <span class="ico"><i class="bi {{ $category->icon }}"></i></span>
                <span class="name">{{ $category->name }}</span>
                <span class="foot"><span>{{ request('category') == $category->id ? 'Dipilih' : 'Jelajahi' }}</span><i class="bi bi-arrow-right"></i></span>
            </a>
        @endforeach
    </div>
    <button type="button" class="g-arrow g-arrow-next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
</div>

{{-- ===================== JASA POPULER =====================
     Sumber datanya HANYA tabel categories, jadi tampilan beranda tidak berubah
     ketika freelancer menambah/menghapus jasa. --}}
@unless($isSearching)
    @php
        $mockByCategory = [
            'desain-grafis' => 'palette',
            'fotografi' => 'photo',
            'pembuatan-presentasi' => 'slide',
            'pengembangan-web' => 'web',
            'penyuntingan-video' => 'video',
        ];
    @endphp

    <section class="g-dark">
        <div class="g-dark-head">
            <div>
                <span class="g-kicker">Jasa Populer</span>
                <h2 class="g-section-title">Jasa populer mahasiswa</h2>
                <p class="sub">Pilih kategori, lalu lihat freelancer mahasiswa yang siap mengerjakan pesananmu.</p>
            </div>
        </div>

        <div class="g-carousel">
            <button type="button" class="g-arrow g-arrow-prev" aria-label="Sebelumnya"><i class="bi bi-chevron-left"></i></button>
            <div class="g-track">
                @foreach($categories as $category)
                    @php $mock = $mockByCategory[$category->slug] ?? null; @endphp
                    <a href="{{ route('home', ['category' => $category->id]) }}" class="g-pop">
                        <div class="g-pop-media">
                            @if($mock)
                                <div class="g-mock">
                                    @if($mock === 'palette')
                                        <div class="m-swatches">
                                            <span class="m-swatch" style="background:#FACC15"></span>
                                            <span class="m-swatch" style="background:#FFFFFF"></span>
                                            <span class="m-swatch" style="background:#858383"></span>
                                            <span class="m-swatch" style="background:#0A0A0A;border-color:rgba(250,204,21,.6);color:#FACC15;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem">G</span>
                                        </div>
                                    @elseif($mock === 'photo')
                                        <div class="m-photo"><i class="bi bi-camera-fill"></i></div>
                                    @elseif($mock === 'slide')
                                        <div class="m-slide">
                                            <div class="m-bar y"></div>
                                            <div style="display:flex;flex-direction:column;gap:5px">
                                                <div class="m-bar"></div>
                                                <div class="m-bar" style="width:75%"></div>
                                            </div>
                                        </div>
                                    @elseif($mock === 'web')
                                        <div class="m-browser">
                                            <div class="top"><span></span><span></span><span></span></div>
                                            <div class="body">
                                                <div class="m-bar y"></div>
                                                <div class="m-bar"></div>
                                                <div class="m-bar" style="width:70%"></div>
                                            </div>
                                        </div>
                                    @elseif($mock === 'video')
                                        <div class="m-phone"><i class="bi bi-play-circle-fill"></i><div class="tl"><span></span></div></div>
                                    @endif
                                </div>
                            @else
                                <i class="bi {{ $category->icon }}"></i>
                            @endif
                        </div>
                        <div class="ttl">{{ $category->name }}</div>
                        <div class="go"><span>Jelajahi kategori</span><i class="bi bi-arrow-up-right"></i></div>
                    </a>
                @endforeach
            </div>
            <button type="button" class="g-arrow g-arrow-next" aria-label="Berikutnya"><i class="bi bi-chevron-right"></i></button>
        </div>
    </section>

    {{-- ===================== FREELANCER TERLARIS (top 5) ===================== --}}
    @if($topSellers->isNotEmpty())
        <section class="mt-5">
            <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-3">
                <div>
                    <span class="g-kicker">Leaderboard</span>
                    <h2 class="g-section-title">Freelancer terlaris</h2>
                </div>
                <a href="{{ route('leaderboard') }}" class="fw-semibold text-decoration-none small">Lihat leaderboard <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="g-panel">
                @include('leaderboard._list', ['users' => $topSellers])
            </div>
        </section>
    @endif

    {{-- ===================== KEUNGGULAN ===================== --}}
    <section class="g-features" id="keunggulan">
        <div class="g-features-head">
            <span class="g-kicker">Mengapa Gawean?</span>
            <h2 class="g-section-title">Keunggulan Gawean</h2>
            <p>Solusi praktis untuk tugas dan proyekmu, dikerjakan langsung oleh mahasiswa.</p>
        </div>
        <div class="row g-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="g-feature">
                    <span class="g-feature-icon"><i class="bi bi-grid-3x3-gap-fill"></i></span>
                    <h3>Multi Kategori Kampus</h3>
                    <p>Akses jasa dari berbagai kategori dan mahasiswa kampus.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="g-feature">
                    <span class="g-feature-icon"><i class="bi bi-lightning-charge-fill"></i></span>
                    <h3>Cepat &amp; Tanpa Ribet</h3>
                    <p>Cari dan pesan jasa dengan cepat, tanpa proses yang berbelit.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="g-feature">
                    <span class="g-feature-icon"><i class="bi bi-wallet2"></i></span>
                    <h3>Tarif Mahasiswa</h3>
                    <p>Harga jasa ramah kantong mahasiswa, dengan dana ditahan sampai pesanan selesai.</p>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="g-feature">
                    <span class="g-feature-icon"><i class="bi bi-patch-check-fill"></i></span>
                    <h3>Koneksi Lokal Kampus</h3>
                    <p>Langsung terhubung dengan freelancer di kampusmu.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== AJAKAN FREELANCER ===================== --}}
    <section class="g-cta">
        <div>
            <span class="pill">Gabung freelancer kampus</span>
            <h2>Punya skill desain, coding, atau video? Hasilkan uang saku tambahan di Gawean.</h2>
            <p>Mulai dari kampusmu sendiri dan terima pesanan langsung dari sesama mahasiswa.</p>
        </div>
        @auth
            <a href="{{ route('services.create') }}" class="btn btn-accent">Tawarkan Jasa Sekarang <i class="bi bi-arrow-right ms-1"></i></a>
        @else
            <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#registerModal">Tawarkan Jasa Sekarang <i class="bi bi-arrow-right ms-1"></i></button>
        @endauth
    </section>
@endunless

{{-- ===================== DAFTAR JASA (hanya saat cari / klik kategori / filter harga) ===================== --}}
@if($isSearching)
    @php $activeCategory = $categories->firstWhere('id', (int) request('category')); @endphp

    <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mt-4 mb-4">
        <div>
            <h1 class="g-page-title">
                @if(request('q'))
                    Hasil untuk "{{ request('q') }}"
                @elseif($activeCategory)
                    Kategori: {{ $activeCategory->name }}
                @else
                    Hasil pencarian
                @endif
                <small class="text-muted fw-normal" style="font-size:1.1rem">({{ $services->total() }})</small>
            </h1>
            <p class="g-page-sub">Temukan mahasiswa bertalenta untuk kebutuhanmu. Klik kartu untuk melihat detail jasa.</p>
        </div>

        <form method="GET" class="g-filter">
            <input type="hidden" name="q" value="{{ request('q') }}">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <span class="rp">Rp</span>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Harga maks" aria-label="Harga maksimal">
            <button class="btn btn-primary btn-sm">Terapkan</button>
            <a href="{{ route('home') }}" class="reset">Reset</a>
        </form>
    </div>

    <div class="row g-4">
        @forelse($services as $service)
            @php $f = $service->user; @endphp
            <div class="col-12 col-md-6 col-lg-4">
                {{-- Seluruh kartu = link ke detail jasa (stretched-link pada judul).
                     Nama freelancer adalah link TERPISAH ke profil (z-index di atas), tanpa <a> bersarang. --}}
                <article class="g-fcard">
                    <h3 class="ttl"><a href="{{ route('services.show', $service) }}" class="stretched-link">{{ $service->title }}</a></h3>

                    <div class="g-fcard-head">
                        <x-avatar :user="$f" :size="44" />
                        <div class="flex-grow-1" style="min-width:0">
                            <div class="nm">
                                <a href="{{ route('freelancers.show', $f) }}" class="owner">{{ $f->name }}</a>
                                <i class="bi bi-patch-check-fill"></i>
                            </div>
                            @if($f->campus)
                                <div class="meta">{{ $f->campus }}</div>
                            @endif
                        </div>
                    </div>

                    <p class="bio">{{ $f->bio ?: \Illuminate\Support\Str::limit(strip_tags($service->description), 110) }}</p>

                    @if(!empty($f->skills))
                        <div class="g-skill-tags">
                            @foreach($f->skills as $skill)
                                <span class="g-skill-tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="price">
                        <small>Mulai dari</small>
                        <strong>Rp {{ number_format($service->price, 0, ',', '.') }}</strong>
                    </div>

                    <div class="foot">
                        <span>
                            @if($f->rating !== null)
                                {{ $f->reviews_total }} Ulasan &middot; <strong><i class="bi bi-star-fill g-star"></i> {{ number_format($f->rating, 1) }}</strong>
                            @else
                                Belum ada ulasan
                            @endif
                        </span>
                        <span class="go">Lihat Detail <i class="bi bi-arrow-right"></i></span>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="g-panel g-empty-state" style="border-style:dashed">
                    <span class="ico"><i class="bi bi-search"></i></span>
                    <h5>Belum ada jasa yang cocok.</h5>
                    <p>Coba ubah filter harga atau reset pencarian untuk melihat semua freelancer mahasiswa.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary"><i class="bi bi-arrow-counterclockwise me-1"></i> Reset Pencarian</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-5">{{ $services->links() }}</div>
@endif
@endsection
