@extends('layouts.app')
@section('title', $freelancer->name)

@section('content')
@php
    // Berjaga-jaga kalau controller tidak mengirim $services
    $services = $services ?? $freelancer->services()->with('category')->latest()->get();

    // Kategori: pakai $category dari route kalau ada, kalau tidak pakai kategori jasa pertama
    $firstCategory = (isset($category) && $category instanceof \App\Models\Category)
        ? $category
        : $services->first()?->category;
    $firstName = explode(' ', trim((string) $freelancer->name))[0] ?? $freelancer->name;

    // campus disimpan sebagai "Jurusan · Kampus"
    [$jurusan, $kampus] = array_pad(explode(' · ', (string) $freelancer->campus), 2, '');
@endphp

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <a href="{{ $firstCategory ? route('home', ['category' => $firstCategory->id]) : route('home') }}"
       class="text-decoration-none fw-semibold small">
        <i class="bi bi-arrow-left"></i> Kembali ke {{ $firstCategory ? 'kategori '.$firstCategory->name : 'beranda' }}
    </a>
    <div class="g-crumbs">
        <a href="{{ route('home') }}">Beranda</a>
        @if($firstCategory)
            <i class="bi bi-chevron-right small"></i>
            <a href="{{ route('home', ['category' => $firstCategory->id]) }}">{{ $firstCategory->name }}</a>
        @endif
        <i class="bi bi-chevron-right small"></i>
        <span class="cur">{{ $freelancer->name }}</span>
    </div>
</div>

{{-- Kepala profil --}}
<div class="g-panel g-profile-head mb-4">
    <div class="d-flex gap-3 gap-md-4 flex-wrap align-items-center">
        <x-avatar :user="$freelancer" :size="88">
            <span class="zap"><i class="bi bi-lightning-charge-fill"></i></span>
        </x-avatar>
        <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <h1 class="mb-0" style="font-size:clamp(1.6rem,3vw,2.2rem)">{{ $freelancer->name }}</h1>
            </div>
            @if($freelancer->campus)
                <div class="mt-1">
                    {{ $jurusan }}@if($kampus !== '') &middot; <strong>{{ $kampus }}</strong>@endif
                </div>
            @endif
            <div class="d-flex flex-wrap gap-3 mt-2 small text-muted">
                @if($freelancer->rating !== null)
                    <span><i class="bi bi-star-fill g-star"></i> <strong class="text-dark">{{ number_format($freelancer->rating, 1) }}</strong> &middot; {{ $freelancer->reviews_total }} ulasan</span>
                @else
                    <span><i class="bi bi-star"></i> Belum ada ulasan</span>
                @endif
                <span><i class="bi bi-briefcase"></i> {{ $services->count() }} layanan</span>
            </div>
        </div>
        <div class="ms-md-auto d-flex gap-2 flex-wrap">
            @auth
                @if(auth()->id() === $freelancer->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i> Ubah profil</a>
                @endif
            @endauth
            <x-chat-button :target="$freelancer" class="btn btn-accent" :label="'Chat '.$firstName" />
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Kolom utama --}}
    <div class="col-lg-8">
        <ul class="nav g-fm-tabs mb-3" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabTentang" type="button"><i class="bi bi-person"></i> Tentang Saya</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabLayanan" type="button"><i class="bi bi-tools"></i> Layanan <span class="count">{{ $services->count() }}</span></button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabUlasan" type="button"><i class="bi bi-star"></i> Ulasan</button></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="tabTentang">
                <div class="g-panel g-panel-pad">
                    <h2 class="g-panel-title">Tentang Freelancer</h2>
                    <p>{{ $freelancer->bio ?: 'Freelancer ini belum menulis deskripsi.' }}</p>
                    @if($jurusan !== '' && $kampus !== '')
                        <p class="text-muted">Saat ini aktif kuliah jurusan {{ $jurusan }} di {{ $kampus }}.</p>
                    @endif

                    @if(!empty($freelancer->skills))
                        <h3 class="h6 mt-4 mb-2"><i class="bi bi-tools"></i> Keahlian</h3>
                        <div class="g-skill-tags">
                            @foreach($freelancer->skills as $skill)
                                <span class="g-skill-tag">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @endif

                    <div class="row g-3 mt-3">
                        <div class="col-sm-4">
                            <div class="g-info-tile">
                                <span class="ico"><i class="bi bi-mortarboard-fill"></i></span>
                                <strong>Mahasiswa Aktif</strong>
                                <span>{{ $kampus !== '' ? $kampus : 'Kampus belum diisi' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="g-info-tile">
                                <span class="ico"><i class="bi bi-shield-check"></i></span>
                                <strong>Dana Ditahan</strong>
                                <span>Dicairkan setelah pesanan selesai</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="g-info-tile">
                                <span class="ico"><i class="bi bi-briefcase-fill"></i></span>
                                <strong>{{ $services->count() }} Layanan</strong>
                                <span>Siap dipesan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="tabLayanan">
                @forelse($services as $service)
                    <div class="g-fm-service g-panel g-panel-pad mb-3">
                        <div>
                            <span class="g-skill-tag">{{ $service->category->name }}</span>
                            <div class="fw-bold fs-5 mt-2" style="font-family:var(--g-font-head);letter-spacing:-.01em">{{ $service->title }}</div>
                            <div class="text-muted">Mulai dari <strong class="text-dark">Rp {{ number_format($service->price, 0, ',', '.') }}</strong></div>
                        </div>
                        <a href="{{ route('services.show', $service) }}" class="btn btn-primary">Lihat &amp; Pesan <i class="bi bi-arrow-right ms-1"></i></a>
                    </div>
                @empty
                    <div class="g-panel g-empty-state">
                        <span class="ico"><i class="bi bi-briefcase"></i></span>
                        <p class="mb-0">Freelancer ini belum punya layanan.</p>
                    </div>
                @endforelse
            </div>

            <div class="tab-pane fade" id="tabUlasan">
                @forelse($reviews as $review)
                    <div class="g-panel g-panel-pad mb-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div class="d-flex align-items-center gap-2">
                                <x-avatar :user="$review->reviewer" :size="36" />
                                <div>
                                    <div class="fw-semibold">{{ $review->reviewer->name }}</div>
                                    <div class="small" aria-label="Rating {{ $review->rating }} dari 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi {{ $i <= $review->rating ? 'bi-star-fill g-star' : 'bi-star' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <span class="small text-muted text-nowrap">{{ \App\Support\Waktu::relatif($review->created_at) }}</span>
                        </div>
                        @if($review->comment)
                            <p class="mb-0 mt-2" style="white-space:pre-line;overflow-wrap:anywhere">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <div class="g-panel g-empty-state">
                        <span class="ico"><i class="bi bi-chat-square-text"></i></span>
                        <p class="mb-0">Belum ada ulasan untuk freelancer ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Kolom samping --}}
    <div class="col-lg-4">
        <div class="g-price-card d-flex flex-column gap-3">
            <div class="g-panel g-panel-pad">
                <div class="d-flex align-items-center gap-2 small fw-semibold mb-3">
                    <span style="width:9px;height:9px;border-radius:50%;background:var(--g-yellow);display:inline-block"></span>
                    {{ $services->count() > 0 ? 'Siap Menerima Order' : 'Belum Ada Layanan' }}
                </div>
                <h2 class="h5 mb-2">Pesan jasa {{ $firstName }}</h2>
                <p class="text-muted small">Pilih layanan yang kamu butuhkan, lalu tulis catatan untuk freelancer saat memesan.</p>

                <ul class="g-side-list mb-4">
                    <li><i class="bi bi-shield-check"></i> Bukti pembayaran diverifikasi admin Gawean.</li>
                    <li><i class="bi bi-lock"></i> Dana ditahan sampai pesanan selesai.</li>
                    <li><i class="bi bi-chat-left-text"></i> Catatan kebutuhan dikirim langsung ke freelancer.</li>
                </ul>

                @if($services->count() > 0)
                    <button type="button" class="btn btn-accent w-100"
                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('[data-bs-target=&quot;#tabLayanan&quot;]')).show(); document.getElementById('tabLayanan').scrollIntoView({behavior:'smooth', block:'start'});">
                        Lihat Layanan <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                @endif
            </div>

            @if($jurusan !== '' || $kampus !== '')
                <div class="g-panel g-panel-pad">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <span class="g-fm-avatar" style="width:42px;height:42px;font-size:1.1rem;border-radius:12px"><i class="bi bi-mortarboard-fill"></i></span>
                        <div>
                            <div class="fw-bold" style="font-family:var(--g-font-head)">Info Kampus</div>
                            <div class="small text-muted">Data yang diisi freelancer</div>
                        </div>
                    </div>
                    <div class="g-kv">
                        @if($kampus !== '')<div><span>Institusi</span><strong>{{ $kampus }}</strong></div>@endif
                        @if($jurusan !== '')<div><span>Program Studi</span><strong>{{ $jurusan }}</strong></div>@endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
