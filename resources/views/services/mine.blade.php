@extends('layouts.app')
@section('title', 'Jasa Saya')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Jasa Saya</span>
</div>

<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <h1 class="g-page-title">Jasa Saya</h1>
        <p class="g-page-sub">Kelola jasa yang kamu tawarkan di Gawean.</p>
    </div>
    <a href="{{ route('services.create') }}" class="btn btn-accent"><i class="bi bi-plus-lg me-1"></i> Tambah Jasa</a>
</div>

<div class="g-panel">
    @if($services->isEmpty())
        <div class="g-empty-state">
            <span class="ico"><i class="bi bi-briefcase"></i></span>
            <h5>Belum ada jasa.</h5>
            <p>Tambahkan jasa pertamamu supaya klien bisa menemukan dan memesannya.</p>
            <a href="{{ route('services.create') }}" class="btn btn-primary px-4">Tambah Jasa <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table g-panel-table mb-0 align-middle">
                <thead>
                    <tr><th>Judul</th><th>Kategori</th><th>Harga</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td style="min-width:200px">
                                <a href="{{ route('services.show', $service) }}" class="fw-bold text-decoration-none" style="font-family:var(--g-font-head)">{{ $service->title }}</a>
                            </td>
                            <td><span class="g-skill-tag">{{ $service->category->name }}</span></td>
                            <td class="fw-bold text-nowrap" style="font-family:var(--g-font-head)">Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('services.destroy', $service) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus jasa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
