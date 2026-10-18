@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <div class="g-stat-card">
            <div class="label mb-2"><i class="bi bi-people-fill me-1"></i> Total Pengguna</div>
            <div class="num">{{ $stats['users'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="g-stat-card">
            <div class="label mb-2"><i class="bi bi-mortarboard-fill me-1"></i> Freelancer</div>
            <div class="num">{{ $stats['freelancers'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="g-stat-card">
            <div class="label mb-2"><i class="bi bi-briefcase-fill me-1"></i> Total Jasa</div>
            <div class="num">{{ $stats['services'] }}</div>
        </div>
    </div>
    <div class="col-6 col-lg">
        <div class="g-stat-card">
            <div class="label mb-2"><i class="bi bi-bag-check-fill me-1"></i> Total Pesanan</div>
            <div class="num">{{ $stats['orders'] }}</div>
        </div>
    </div>
    <div class="col-12 col-lg">
        <a href="{{ route('admin.orders') }}" class="text-decoration-none d-block h-100">
            <div class="g-stat-card alert-card">
                <div class="label mb-2"><i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi</div>
                <div class="num">{{ $stats['pending_payments'] }}</div>
            </div>
        </a>
    </div>
</div>

<div class="g-panel p-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h2 class="h5 mb-0">Pesanan Terbaru</h2>
        <a href="{{ route('admin.orders') }}" class="fw-semibold small text-decoration-none">Lihat semua pesanan <i class="bi bi-arrow-right"></i></a>
    </div>

    @if($recentOrders->isEmpty())
        <p class="text-muted mb-0">Belum ada pesanan.</p>
    @else
        <div class="table-responsive">
            <table class="table g-panel-table mb-0 align-middle">
                <thead><tr><th>Jasa</th><th>Pembeli</th><th>Harga</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->service->title }}</td>
                            <td>{{ $order->buyer->name }}</td>
                            <td class="fw-bold text-nowrap">Rp {{ number_format($order->price, 0, ',', '.') }}</td>
                            <td><span class="g-status-badge g-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                            <td class="small text-muted text-nowrap">{{ $order->created_at->format('d M Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
