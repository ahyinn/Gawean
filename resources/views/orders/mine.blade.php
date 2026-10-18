@extends('layouts.app')
@section('title', 'Pesanan Saya')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Pesanan Saya</span>
</div>

<div class="mb-4">
    <h1 class="g-page-title">Pesanan Saya</h1>
    <p class="g-page-sub">Pantau status pembayaran dan progres pengerjaan semua pesananmu di satu tempat.</p>
</div>

<style>
    .g-rate { display:inline-flex; flex-direction:row-reverse; gap:.15rem; font-size:1.6rem; }
    .g-rate input { position:absolute; opacity:0; pointer-events:none; }
    .g-rate label { cursor:pointer; color:#d1d5db; line-height:1; }
    .g-rate input:checked ~ label, .g-rate label:hover, .g-rate label:hover ~ label { color:var(--g-yellow); -webkit-text-stroke:.6px var(--g-ink); }
    .g-rate input:focus-visible + label { outline:2px solid var(--g-ink); outline-offset:2px; }
</style>

<div class="g-panel">
    @if($orders->isEmpty())
        <div class="g-empty-state">
            <span class="ico"><i class="bi bi-bag-check"></i></span>
            <h5>Belum ada pesanan. Yuk cari jasa dulu!</h5>
            <p>Temukan freelancer mahasiswa berbakat untuk proyek, tugas, dan kebutuhan brandingmu.</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-4">Cari Jasa <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table g-panel-table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Jasa</th>
                        <th>Freelancer</th>
                        <th>Harga</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td style="min-width:200px">
                                <a href="{{ route('services.show', $order->service) }}" class="fw-bold text-decoration-none" style="font-family:var(--g-font-head)">
                                    {{ $order->service->title }}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-avatar :user="$order->service->user" :size="30" />
                                    <a href="{{ route('freelancers.show', $order->service->user) }}" class="fw-semibold text-decoration-none">{{ $order->service->user->name }}</a>
                                </div>
                            </td>
                            <td class="fw-bold text-nowrap" style="font-family:var(--g-font-head)">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                            <td>
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
                            </td>
                            <td>
                                @if($order->status === 'menunggu')
                                    <span class="g-status-badge g-status-menunggu"><i class="bi bi-hourglass-split"></i> Menunggu Konfirmasi</span>
                                @elseif($order->status === 'diterima')
                                    <span class="g-status-badge g-status-diterima"><i class="bi bi-check2-circle"></i> Diterima</span>
                                @elseif($order->status === 'selesai')
                                    <span class="g-status-badge g-status-selesai"><i class="bi bi-check2-all"></i> Selesai</span>
                                @else
                                    <span class="g-status-badge g-status-ditolak"><i class="bi bi-x-circle"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="small text-muted text-nowrap">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="text-end">
                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                    <x-chat-button :target="$order->service->user" class="btn btn-sm btn-outline-primary text-nowrap" />
                                    @if($order->status === 'selesai')
                                        @if($order->review)
                                            <span class="small text-nowrap align-self-center" title="Ulasanmu" aria-label="Rating {{ $order->review->rating }} dari 5">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi {{ $i <= $order->review->rating ? 'bi-star-fill g-star' : 'bi-star' }}"></i>
                                                @endfor
                                            </span>
                                        @else
                                            <button type="button" class="btn btn-sm btn-accent text-nowrap" data-bs-toggle="collapse" data-bs-target="#review-{{ $order->id }}" aria-expanded="{{ $errors->has('rating') && old('order') == $order->id ? 'true' : 'false' }}">
                                                <i class="bi bi-star me-1"></i> Beri Ulasan
                                            </button>
                                        @endif
                                    @endif
                                    @if($order->canUploadProof())
                                        <a href="{{ route('orders.pay', $order) }}" class="btn btn-sm btn-primary text-nowrap">
                                            {{ $order->payment_status === 'ditolak_verifikasi' ? 'Upload Ulang' : 'Bayar Sekarang' }}
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @if($order->status === 'selesai' && ! $order->review)
                            <tr class="collapse {{ $errors->has('rating') && old('order') == $order->id ? 'show' : '' }}" id="review-{{ $order->id }}">
                                <td colspan="7">
                                    <form method="POST" action="{{ route('reviews.store', $order) }}" class="g-review-form py-2">
                                        @csrf
                                        <input type="hidden" name="order" value="{{ $order->id }}">
                                        <div class="fw-semibold mb-2">Bagaimana hasil kerja {{ $order->service->user->name }}?</div>
                                        <div class="g-rate mb-2">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio" name="rating" id="rate-{{ $order->id }}-{{ $i }}" value="{{ $i }}" {{ old('order') == $order->id && old('rating') == $i ? 'checked' : '' }}>
                                                <label for="rate-{{ $order->id }}-{{ $i }}" title="{{ $i }} bintang"><i class="bi bi-star-fill"></i></label>
                                            @endfor
                                        </div>
                                        @if(old('order') == $order->id)
                                            @error('rating')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                                            @error('comment')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                                        @endif
                                        <textarea name="comment" rows="2" maxlength="1000" class="form-control mb-2" placeholder="Tulis ulasan (opsional)">{{ old('order') == $order->id ? old('comment') : '' }}</textarea>
                                        <button class="btn btn-sm btn-primary">Kirim Ulasan</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
