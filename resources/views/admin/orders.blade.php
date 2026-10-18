@extends('admin.layout')
@section('title', 'Pesanan')

@section('content')
<div class="d-flex flex-column gap-3">
    @forelse($orders as $order)
        <div class="g-panel p-3 p-md-4">
            <div class="row g-3 align-items-start">
                <div class="col-md-2">
                    @if($order->payment_proof)
                        <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank" class="d-block">
                            <img src="{{ asset('storage/'.$order->payment_proof) }}" class="img-fluid rounded border" alt="Bukti pembayaran">
                        </a>
                        <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank" class="small text-decoration-none">
                            <i class="bi bi-zoom-in"></i> Lihat penuh
                        </a>
                    @else
                        <div class="g-no-proof">
                            <i class="bi bi-image"></i>
                            <span class="small text-muted">Belum ada bukti</span>
                        </div>
                    @endif
                </div>

                <div class="col-md-4">
                    <div class="fw-semibold">{{ $order->service->title }}</div>
                    <div class="small text-muted mb-1">Freelancer: {{ $order->service->user->name }}</div>
                    <div class="small text-muted mb-1">Pembeli: {{ $order->buyer->name }}</div>
                    @if($order->payment_method)
                        <div class="small text-muted mb-1">Metode: {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</div>
                    @endif
                    @if($order->note)
                        <div class="small text-muted fst-italic mt-1">Catatan: {{ $order->note }}</div>
                    @endif
                    <div class="fw-semibold mt-2">Rp {{ number_format($order->price, 0, ',', '.') }}</div>
                    <div class="small text-muted">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="col-md-3">
                    <div class="mb-2">
                        <span class="small text-muted d-block mb-1">Status Pembayaran</span>
                        @switch($order->payment_status)
                            @case('belum_bayar')
                                <span class="g-pay-badge g-pay-warn"><i class="bi bi-exclamation-circle-fill"></i> Belum Bayar</span>
                                @break
                            @case('menunggu_verifikasi')
                                <span class="g-pay-badge g-pay-info"><i class="bi bi-hourglass-split"></i> Menunggu Verifikasi</span>
                                @break
                            @case('ditolak_verifikasi')
                                <span class="g-pay-badge g-pay-danger"><i class="bi bi-x-circle-fill"></i> Bukti Ditolak</span>
                                @break
                            @case('held')
                                <span class="g-pay-badge g-pay-primary"><i class="bi bi-shield-lock-fill"></i> Dana Ditahan</span>
                                @break
                            @case('dicairkan')
                                <span class="g-pay-badge g-pay-success"><i class="bi bi-check-circle-fill"></i> Dana Dicairkan</span>
                                @break
                            @case('refunded')
                                <span class="g-pay-badge g-pay-muted"><i class="bi bi-arrow-counterclockwise"></i> Dana Dikembalikan</span>
                                @break
                        @endswitch
                    </div>
                    <div>
                        <span class="small text-muted d-block mb-1">Status Pesanan</span>
                        <span class="g-status-badge g-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    </div>
                </div>

                <div class="col-md-3">
                    @if($order->payment_status === 'menunggu_verifikasi')
                        <form method="POST" action="{{ route('admin.payments.verify', $order) }}" class="mb-2">
                            @csrf @method('PATCH')
                            <div class="g-verify-checklist mb-2">
                                <label class="form-check d-flex align-items-start gap-2">
                                    <input class="form-check-input mt-1" type="checkbox" required
                                           onchange="document.getElementById('verifyBtn{{ $order->id }}').disabled = !this.checked">
                                    <span class="small">Bukti valid &amp; nominal sesuai Rp {{ number_format($order->price, 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <button type="submit" id="verifyBtn{{ $order->id }}" class="btn btn-success btn-sm w-100 mb-2" disabled>
                                <i class="bi bi-check-circle"></i> Verifikasi &amp; Tahan Dana
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.payments.reject', $order) }}">
                            @csrf @method('PATCH')
                            <input type="text" name="reason" class="form-control form-control-sm mb-2" placeholder="Alasan penolakan">
                            <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Tolak bukti pembayaran ini?')">
                                <i class="bi bi-x-circle"></i> Tolak Bukti
                            </button>
                        </form>
                    @elseif($order->payment_status === 'belum_bayar')
                        <p class="small text-muted fst-italic mb-0">Menunggu pembeli membayar.</p>
                    @elseif($order->payment_status === 'ditolak_verifikasi')
                        <p class="small text-muted fst-italic mb-0">Menunggu pembeli upload ulang bukti.</p>
                    @elseif($order->payment_status === 'held')
                        <p class="small text-muted fst-italic mb-0"><i class="bi bi-check2"></i> Sudah diverifikasi, menunggu keputusan freelancer.</p>
                    @elseif($order->payment_status === 'dicairkan')
                        <p class="small text-success fst-italic mb-0"><i class="bi bi-check2-all"></i> Pesanan selesai, dana sudah cair.</p>
                    @elseif($order->payment_status === 'refunded')
                        <p class="small text-muted fst-italic mb-0"><i class="bi bi-arrow-counterclockwise"></i> Pesanan ditolak freelancer, dana dikembalikan.</p>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="g-panel p-5 text-center text-muted">
            <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
            <p class="mt-3 mb-0">Belum ada pesanan.</p>
        </div>
    @endforelse
</div>

<div class="mt-3">{{ $orders->links() }}</div>

<style>
    .g-no-proof { aspect-ratio:1; border:1px dashed var(--g-border); border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:.4rem; color:var(--g-muted); }
    .g-no-proof i { font-size:1.5rem; }
    .g-verify-checklist { background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:.5rem .65rem; }
</style>
@endsection