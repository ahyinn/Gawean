@extends('admin.layout')
@section('title', 'Verifikasi Pembayaran')

@section('content')
@if($pendingPayments->isEmpty())
    <div class="g-panel p-5 text-center text-muted">
        <i class="bi bi-check2-circle" style="font-size:2.5rem;"></i>
        <p class="mt-3 mb-0">Tidak ada pembayaran yang menunggu verifikasi.</p>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($pendingPayments as $order)
            <div class="g-panel p-3 p-md-4">
                <div class="row g-3 align-items-start">
                    <div class="col-md-3">
                        @if($order->payment_proof)
                            <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank" class="d-block">
                                <img src="{{ asset('storage/'.$order->payment_proof) }}" class="img-fluid rounded border" alt="Bukti pembayaran">
                            </a>
                            <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank" class="small text-decoration-none">
                                <i class="bi bi-zoom-in"></i> Lihat ukuran penuh
                            </a>
                        @else
                            <div class="text-muted small">Tidak ada bukti</div>
                        @endif
                    </div>

                    <div class="col-md-5">
                        <div class="fw-semibold">{{ $order->service->title }}</div>
                        <div class="small text-muted mb-1">Freelancer: {{ $order->service->user->name }}</div>
                        <div class="small text-muted mb-1">Pembeli: {{ $order->buyer->name }}</div>
                        <div class="small text-muted mb-2">Metode: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>

                        @if($order->note)
                            <div class="g-order-note mb-2">
                                <span class="small text-muted d-block mb-1"><i class="bi bi-chat-left-text"></i> Catatan pembeli:</span>
                                <span class="small">{{ $order->note }}</span>
                            </div>
                        @endif

                        <div class="g-pay-amount-box">
                            <span class="small text-muted d-block">Nominal yang harus cocok dengan bukti transfer</span>
                            <strong class="fs-4 text-primary">Rp {{ number_format($order->price, 0, ',', '.') }}</strong>
                        </div>

                        <div class="small text-muted mt-2">Dikirim: {{ $order->updated_at->format('d M Y, H:i') }}</div>
                    </div>

                    <div class="col-md-4">
                        <form method="POST" action="{{ route('admin.payments.verify', $order) }}" id="verifyForm{{ $order->id }}">
                            @csrf @method('PATCH')

                            <div class="g-verify-checklist mb-2">
                                <label class="form-check d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="checkbox" required
                                           onchange="document.getElementById('verifyBtn{{ $order->id }}').disabled = !this.checked">
                                    <span class="small">Saya sudah memeriksa: bukti transfer terlihat asli/valid, dan nominalnya sesuai dengan <strong>Rp {{ number_format($order->price, 0, ',', '.') }}</strong>.</span>
                                </label>
                            </div>

                            <button type="submit" id="verifyBtn{{ $order->id }}" class="btn btn-success btn-sm w-100 mb-2" disabled>
                                <i class="bi bi-check-circle"></i> Verifikasi &amp; Tahan Dana
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.payments.reject', $order) }}">
                            @csrf @method('PATCH')
                            <input type="text" name="reason" class="form-control form-control-sm mb-2"
                                   placeholder="Alasan penolakan (contoh: nominal tidak sesuai)">
                            <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Tolak bukti pembayaran ini?')">
                                <i class="bi bi-x-circle"></i> Tolak Bukti
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<style>
    .g-pay-amount-box { background:var(--g-soft); border-radius:10px; padding:.75rem 1rem; display:inline-block; }
    .g-verify-checklist { background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:.65rem .75rem; }
    .g-order-note { background:#f8fafc; border:1px solid var(--g-border); border-radius:10px; padding:.6rem .75rem; }
</style>
@endsection