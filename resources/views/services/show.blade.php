@extends('layouts.app')
@section('title', $service->title)

@section('content')
@php
    $owner = $service->user;

    // Jasa lain di kategori yang sama (data asli)
    $related = \App\Models\Service::with(['user', 'category'])
        ->where('category_id', $service->category_id)
        ->where('id', '!=', $service->id)
        ->latest()->take(3)->get();
@endphp

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <a href="{{ route('home') }}" class="text-decoration-none fw-semibold small"><i class="bi bi-arrow-left"></i> Semua jasa</a>
    <div class="g-crumbs">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="bi bi-chevron-right small"></i>
        <a href="{{ route('home', ['category' => $service->category_id]) }}">{{ $service->category->name }}</a>
    </div>
</div>

<div class="row g-4">
    {{-- Kolom kiri --}}
    <div class="col-lg-8">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="g-skill-tag">{{ $service->category->name }}</span>
            @if($owner->campus)
                <span class="small text-muted align-self-center">{{ $owner->campus }}</span>
            @endif
        </div>

        <h1 class="g-page-title mb-3">{{ $service->title }}</h1>

        <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
            <x-avatar :user="$owner" :size="44" />
            <div>
                <a href="{{ route('freelancers.show', $owner) }}" class="fw-bold text-decoration-none" style="font-family:var(--g-font-head)">{{ $owner->name }}</a>
                <div class="small text-muted">
                    @if($owner->rating !== null)
                        <i class="bi bi-star-fill g-star"></i> <strong class="text-dark">{{ number_format($owner->rating, 1) }}</strong> ({{ $owner->reviews_total }} ulasan)
                    @else
                        Belum ada ulasan
                    @endif
                </div>
            </div>
        </div>

        @if($service->image)
            <img src="{{ asset('storage/'.$service->image) }}" class="w-100 mb-4"
                 style="max-height:440px;object-fit:cover;border-radius:16px;border:1px solid var(--g-border)" alt="{{ $service->title }}">
        @endif

        <h2 class="h4 mb-3">Tentang jasa ini</h2>
        <p style="white-space:pre-line;max-width:72ch">{{ $service->description }}</p>

        {{-- Alur pesanan: urutan sebenarnya di Gawean --}}
        <h2 class="h4 mt-5 mb-3">Alur pengerjaan sederhana</h2>
        <div class="g-panel g-panel-pad">
            <div class="g-steps">
                <div class="g-step">
                    <div class="no">1</div>
                    <h6>Pesan &amp; bayar</h6>
                    <p>Kirim pesanan, pilih metode bayar, lalu unggah bukti transfer.</p>
                </div>
                <div class="g-step">
                    <div class="no">2</div>
                    <h6>Admin verifikasi</h6>
                    <p>Admin memeriksa bukti. Dana ditahan Gawean dan pesanan diteruskan.</p>
                </div>
                <div class="g-step">
                    <div class="no">3</div>
                    <h6>Dikerjakan &amp; selesai</h6>
                    <p>Freelancer menerima pesanan, mengerjakan, lalu menandainya selesai.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Kolom kanan: kotak harga --}}
    <div class="col-lg-4">
        <div class="g-price-card d-flex flex-column gap-3">
            <div class="g-panel g-panel-pad">
                <div class="small fw-bold text-muted mb-1" style="letter-spacing:.06em;text-transform:uppercase;font-family:var(--g-font-head)">Harga jasa</div>
                <div class="amount mb-4">Rp {{ number_format($service->price, 0, ',', '.') }}</div>

                @auth
                    @if(auth()->id() === $service->user_id)
                        <div class="alert alert-secondary small mb-0">Ini jasa milik kamu sendiri.</div>
                    @else
                        <form method="POST" action="{{ route('orders.store', $service) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Catatan untuk freelancer <span class="text-muted fw-normal">(opsional)</span></label>
                                <textarea name="note" rows="3" class="form-control @error('note') is-invalid @enderror"
                                          placeholder="Contoh: butuh selesai sebelum tanggal 30">{{ old('note') }}</textarea>
                                @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button class="btn btn-primary w-100 py-2">Pesan Jasa <i class="bi bi-arrow-right ms-1"></i></button>
                        </form>
                    @endif
                @else
                    <button type="button" class="btn btn-primary w-100 py-2" data-bs-toggle="modal" data-bs-target="#loginModal">Masuk untuk Pesan <i class="bi bi-arrow-right ms-1"></i></button>
                    <p class="small text-muted text-center mt-3 mb-0">
                        Belum punya akun?
                        <a href="#" class="g-link-u" data-bs-toggle="modal" data-bs-target="#registerModal">Daftar akun baru</a>
                    </p>
                @endauth
            </div>

            @if(auth()->guest() || auth()->id() !== $service->user_id)
                <div class="g-panel g-panel-pad">
                    <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
                        <div class="small"><strong>Ada yang mau ditanyakan?</strong><br><span class="text-muted">Chat dulu dengan {{ $owner->name }}.</span></div>
                        <x-chat-button :target="$owner" label="Chat" />
                    </div>
                </div>
            @endif

            <div class="g-panel g-panel-pad">
                <ul class="g-side-list">
                    <li><i class="bi bi-shield-check"></i> <span>Bukti pembayaran diverifikasi admin Gawean sebelum pesanan diteruskan.</span></li>
                    <li><i class="bi bi-lock"></i> <span>Dana ditahan sampai pesanan selesai.</span></li>
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Jasa lain di kategori yang sama --}}
@if($related->isNotEmpty())
    <section class="mt-5 pt-3">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div>
                <span class="g-kicker">Jasa Terkait</span>
                <h2 class="g-section-title" style="font-size:1.6rem">Layanan lain di {{ $service->category->name }}</h2>
            </div>
            <a href="{{ route('home', ['category' => $service->category_id]) }}" class="fw-semibold text-decoration-none small">Lihat semua <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-4">
            @foreach($related as $r)
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="{{ route('services.show', $r) }}" class="g-svc-card">
                        <div class="img">
                            @if($r->image)
                                <img src="{{ asset('storage/'.$r->image) }}" alt="{{ $r->title }}">
                            @else
                                <i class="bi {{ $r->category->icon }}"></i>
                            @endif
                        </div>
                        <div class="bd">
                            <span class="tg">{{ $r->category->name }}</span>
                            <div class="tt">{{ $r->title }}</div>
                            <div class="by">oleh {{ $r->user->name }}</div>
                            <div class="pr">Rp {{ number_format($r->price, 0, ',', '.') }}</div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
