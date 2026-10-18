@extends('layouts.app')
@section('title', 'Leaderboard Freelancer - Gawean')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Leaderboard</span>
</div>

<div class="mb-4">
    <span class="g-kicker">Top 10</span>
    <h1 class="g-page-title">Freelancer terlaris</h1>
    <p class="g-page-sub">Diurutkan dari jumlah pesanan berstatus selesai. Jika jumlahnya sama, freelancer dengan rating lebih tinggi berada di atas.</p>
</div>

<div class="g-panel">
    @if($topSellers->isEmpty())
        <div class="g-empty-state">
            <span class="ico"><i class="bi bi-trophy"></i></span>
            <h5>Belum ada pesanan yang selesai.</h5>
            <p>Leaderboard terisi begitu freelancer menyelesaikan pesanan pertamanya.</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-4">Jelajah Jasa <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    @else
        @include('leaderboard._list', ['users' => $topSellers])
    @endif
</div>
@endsection
