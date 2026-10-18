@extends('layouts.app')
@section('title', 'Pembayaran Pesanan')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('orders.mine') }}"><i class="bi bi-arrow-left"></i> Pesanan Saya</a>
    <span>/</span>
    <span class="cur">Pembayaran Pesanan</span>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        @if($order->payment_status === \App\Models\Order::PAY_MENUNGGU_VERIFIKASI)
            {{-- Sudah upload bukti, tinggal tunggu admin --}}
            <div class="g-panel g-empty-state py-5">
                <span class="ico" style="background:var(--g-yellow);border-color:var(--g-ink)"><i class="bi bi-hourglass-split"></i></span>
                <h5>Menunggu Verifikasi Admin</h5>
                <p class="mb-0">Bukti pembayaranmu sedang diperiksa. Kamu akan bisa lanjut begitu admin memverifikasinya.</p>
            </div>
        @else
            <div class="g-panel g-panel-pad">
                <span class="g-kicker">Pembayaran</span>
                <h1 class="g-page-title mb-2" style="font-size:clamp(1.6rem,3vw,2.2rem)">Pilih metode &amp; unggah bukti</h1>
                <p class="text-muted mb-4">Setelah bukti dikirim, admin memeriksanya sebelum dana ditahan dan pesanan diteruskan ke freelancer.</p>

                @if($order->payment_status === \App\Models\Order::PAY_DITOLAK_VERIFIKASI)
                    <div class="alert alert-danger">
                        <strong>Bukti sebelumnya ditolak.</strong>
                        <div class="small mt-1">{{ $order->verification_note }}</div>
                        <div class="small mt-1">Silakan unggah ulang bukti yang jelas dan sesuai.</div>
                    </div>
                @endif

                @error('proof')
                    <div class="alert alert-danger py-2 small">{{ $message }}</div>
                @enderror
                @error('payment_method')
                    <div class="alert alert-danger py-2 small">{{ $message }}</div>
                @enderror

                <form method="POST" action="{{ route('orders.confirmPayment', $order) }}" enctype="multipart/form-data">
                    @csrf

                    <h2 class="h6 mb-3">Metode pembayaran</h2>
                    <div class="d-flex flex-column gap-2 mb-4">
                        @foreach($methods as $key => $method)
                            <label class="g-pay-option">
                                <input type="radio" name="payment_method" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }}>
                                <span class="g-pay-icon"><i class="bi {{ $method['icon'] }}"></i></span>
                                <span class="fw-semibold">{{ $method['label'] }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between align-items-baseline mb-2">
                        <h2 class="h6 mb-0">Unggah bukti transfer</h2>
                        <span class="form-text mt-0">JPG/PNG, maksimal 4MB</span>
                    </div>
                    <div class="g-drop mb-4">
                        <span class="ico"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                        <div class="fw-semibold mb-1">Pilih foto bukti transfer</div>
                        <div class="small text-muted mb-3">Pastikan nominal Rp {{ number_format($order->price, 0, ',', '.') }} terlihat jelas.</div>
                        <input type="file" name="proof" accept="image/*" class="form-control">
                    </div>

                    <div class="alert alert-secondary small d-flex gap-2">
                        <i class="bi bi-shield-check"></i>
                        <span>Setelah bukti dikirim, admin akan memverifikasi keabsahannya sebelum dana ditahan dan pesanan diteruskan ke freelancer.</span>
                    </div>

                    <button class="btn btn-primary w-100 py-3">Kirim Bukti Pembayaran <i class="bi bi-arrow-right ms-1"></i></button>
                </form>
            </div>
        @endif
    </div>

    <div class="col-lg-5">
        <div class="g-price-card">
            <div class="g-panel g-panel-pad">
                <h2 class="g-panel-title">Ringkasan Pesanan</h2>

                <span class="g-skill-tag">{{ $order->service->category->name ?? 'Jasa' }}</span>
                <p class="mt-2 mb-1 fw-bold" style="font-family:var(--g-font-head);font-size:1.1rem;line-height:1.3">{{ $order->service->title }}</p>
                <p class="text-muted small mb-3"><i class="bi bi-person-circle"></i> {{ $order->service->user->name }}</p>

                @if($order->note)
                    <div class="g-kv mb-3" style="padding:.75rem 1rem">
                        <div style="display:block;border:0;padding:0">
                            <span class="small d-block mb-1">Catatan kebutuhan</span>
                            <em class="small">{{ $order->note }}</em>
                        </div>
                    </div>
                @endif

                <div class="g-total">
                    <div>
                        <small>Total Bayar</small>
                        <strong>Rp {{ number_format($order->price, 0, ',', '.') }}</strong>
                    </div>
                    <span class="ico"><i class="bi bi-cash-stack"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
