@extends('layouts.app')
@section('title', 'Notifikasi')

@section('content')
@php $unread = auth()->user()->unreadNotifications()->count(); @endphp

<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Notifikasi</span>
</div>

<div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
        <h1 class="g-page-title">Notifikasi</h1>
        <p class="g-page-sub">Kabar terbaru soal pesanan dan pembayaranmu.</p>
    </div>
    @if($unread > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button class="btn btn-outline-primary btn-sm"><i class="bi bi-check2-all me-1"></i> Tandai semua dibaca</button>
        </form>
    @endif
</div>

<div class="g-panel">
    @forelse($notifications as $n)
        @php
            $icon = preg_match('/^bi-[a-z0-9-]+$/', (string) ($n->data['icon'] ?? '')) ? $n->data['icon'] : 'bi-bell';
        @endphp
        <a href="{{ route('notifications.open', $n->id) }}" class="g-notif {{ $n->read_at ? '' : 'unread' }}">
            <span class="ico"><i class="bi {{ $icon }}"></i></span>
            <div class="body">
                <div class="ttl">{{ $n->data['title'] ?? 'Notifikasi' }}</div>
                <div class="msg">{{ $n->data['message'] ?? '' }}</div>
            </div>
            <span class="when" title="{{ \App\Support\Waktu::lengkap($n->created_at) }}">{{ \App\Support\Waktu::relatif($n->created_at) }}</span>
        </a>
    @empty
        <div class="g-empty-state">
            <span class="ico"><i class="bi bi-bell"></i></span>
            <h5>Belum ada notifikasi.</h5>
            <p>Notifikasi muncul saat ada pesanan baru, pembayaran diverifikasi, atau status pesanan berubah.</p>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $notifications->links() }}</div>
@endsection
