@extends('layouts.app')
@section('title', 'Chat')

@section('content')
@php $me = auth()->user(); @endphp

<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Chat</span>
</div>

<div class="mb-4">
    <h1 class="g-page-title">Chat</h1>
    <p class="g-page-sub">Percakapanmu dengan freelancer dan pembeli.</p>
</div>

<div class="g-panel">
    @forelse($conversations as $c)
        @php
            $other = $c->otherUser($me);
            $last = $c->latestMessage;
        @endphp
        <a href="{{ route('chat.show', $c) }}" class="g-conv {{ $c->unread_count > 0 ? 'unread' : '' }}">
            <x-avatar :user="$other" :size="48" />
            <div class="body">
                <div class="nm">{{ $other->name }}</div>
                <div class="pv">
                    @if($last)
                        @if($last->sender_id === $me->id)Kamu: @endif{{ \Illuminate\Support\Str::limit($last->body, 80) }}
                    @else
                        Belum ada pesan
                    @endif
                </div>
            </div>
            <div class="d-flex flex-column align-items-end gap-1">
                <span class="when">{{ \App\Support\Waktu::chat($last?->created_at ?? $c->created_at) }}</span>
                @if($c->unread_count > 0)
                    <span class="g-count">{{ $c->unread_count }}</span>
                @endif
            </div>
        </a>
    @empty
        <div class="g-empty-state">
            <span class="ico"><i class="bi bi-chat-dots"></i></span>
            <h5>Belum ada percakapan.</h5>
            <p>Mulai chat dari halaman detail jasa, profil freelancer, atau daftar pesananmu.</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-4">Jelajah Jasa <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
    @endforelse
</div>
@endsection
