@extends('layouts.app')
@section('title', $client->name)

@section('content')
@php [$jurusan, $kampus] = $client->campusParts(); @endphp

<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">{{ $client->name }}</span>
</div>

<div class="g-panel g-profile-head" style="max-width:720px">
    <div class="d-flex gap-3 gap-md-4 flex-wrap align-items-center">
        <x-avatar :user="$client" :size="88" />
        <div class="flex-grow-1" style="min-width:0">
            <span class="g-kicker">Klien Gawean</span>
            <h1 class="mb-0" style="font-size:clamp(1.5rem,3vw,2rem)">{{ $client->name }}</h1>
            @if($client->campus)
                <div class="mt-1">{{ $jurusan }}@if($kampus !== '') &middot; <strong>{{ $kampus }}</strong>@endif</div>
            @endif
            <div class="small text-muted mt-1"><i class="bi bi-calendar3"></i> Bergabung {{ \App\Support\Waktu::lokal($client->created_at)->translatedFormat('F Y') }}</div>
        </div>
    </div>

    <h2 class="h6 mt-4 mb-2">Tentang</h2>
    <p class="mb-0" style="white-space:pre-line;overflow-wrap:anywhere">{{ $client->bio ?: 'Klien ini belum menulis deskripsi.' }}</p>

    <div class="mt-4 d-flex gap-2 flex-wrap">
        @auth
            @if(auth()->id() === $client->id)
                <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i> Ubah profil</a>
            @endif
        @endauth
        <x-chat-button :target="$client" class="btn btn-accent btn-sm" />
    </div>
</div>
@endsection
