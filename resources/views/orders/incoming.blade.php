@extends('layouts.app')
@section('title', 'Pesanan Masuk')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Pesanan Masuk</span>
</div>

<div class="mb-4">
    <h1 class="g-page-title">Pesanan Masuk</h1>
    <p class="g-page-sub">Pesanan dari pembeli untuk jasamu. Kamu bisa menerima atau menolak setelah pembayaran diverifikasi admin.</p>
</div>

@if($orders->isEmpty())
    <div class="g-panel">
        <div class="g-empty-state">
            <span class="ico"><i class="bi bi-inbox"></i></span>
            <h5>Belum ada pesanan masuk.</h5>
            <p>Pesanan akan muncul di sini begitu ada pembeli yang memesan jasamu.</p>
            <a href="{{ route('services.mine') }}" class="btn btn-primary px-4">Kelola Jasa Saya <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    </div>
@else
    <div class="d-flex flex-column gap-3">
        @foreach($orders as $order)
            <div class="g-panel g-panel-pad">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-2">
                    <div class="d-flex align-items-center gap-3" style="min-width:0">
                        <x-avatar :user="$order->buyer" :size="44" />
                        <div style="min-width:0">
                            <div class="fw-bold" style="font-family:var(--g-font-head)">{{ $order->service->title }}</div>
                            <div class="small text-muted">
                                Dari <a href="{{ route('clients.show', $order->buyer) }}" class="fw-semibold text-decoration-none">{{ $order->buyer->name }}</a>
                                &middot; Rp {{ number_format($order->price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-start align-items-sm-end gap-1">
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

                        @if($order->status === 'menunggu')
                            <span class="g-status-badge g-status-menunggu"><i class="bi bi-hourglass-split"></i> Menunggu Konfirmasi</span>
                        @elseif($order->status === 'diterima')
                            <span class="g-status-badge g-status-diterima"><i class="bi bi-check2-circle"></i> Diterima</span>
                        @elseif($order->status === 'selesai')
                            <span class="g-status-badge g-status-selesai"><i class="bi bi-check2-all"></i> Selesai</span>
                        @else
                            <span class="g-status-badge g-status-ditolak"><i class="bi bi-x-circle"></i> Ditolak</span>
                        @endif
                    </div>
                </div>

                @if($order->note)
                    <p class="small text-muted mb-3 mt-2" style="overflow-wrap:anywhere">Catatan: {{ $order->note }}</p>
                @endif

                <div class="d-flex flex-wrap align-items-center gap-2 mt-3 pt-3 border-top">
                    @if($order->isHeld())
                        @if($order->status === 'menunggu')
                            <form method="POST" action="{{ route('orders.updateStatus', $order) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="diterima">
                                <button class="btn btn-sm btn-primary">Terima</button>
                            </form>
                            <form method="POST" action="{{ route('orders.updateStatus', $order) }}"
                                  onsubmit="return confirm('Tolak pesanan ini? Dana akan dikembalikan ke pembeli.')">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="ditolak">
                                <button class="btn btn-sm btn-outline-danger">Tolak</button>
                            </form>
                        @elseif($order->status === 'diterima')
                            <form method="POST" action="{{ route('orders.updateStatus', $order) }}">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="selesai">
                                <button class="btn btn-sm btn-success">Tandai Selesai</button>
                            </form>
                        @endif
                    @elseif($order->payment_status === 'menunggu_verifikasi')
                        <p class="small text-muted fst-italic mb-0"><i class="bi bi-shield-exclamation"></i> Menunggu admin memverifikasi bukti pembayaran pembeli.</p>
                    @elseif($order->payment_status === 'ditolak_verifikasi')
                        <p class="small text-muted fst-italic mb-0"><i class="bi bi-shield-x"></i> Bukti pembayaran pembeli ditolak admin, menunggu pembeli upload ulang.</p>
                    @elseif($order->payment_status === 'belum_bayar')
                        <p class="small text-muted fst-italic mb-0"><i class="bi bi-clock-history"></i> Menunggu pembeli menyelesaikan pembayaran.</p>
                    @endif

                    <div class="ms-auto">
                        <x-chat-button :target="$order->buyer" class="btn btn-sm btn-outline-primary" label="Chat pembeli" />
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
