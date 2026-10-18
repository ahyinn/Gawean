@extends('layouts.app')
@section('title', 'Tambah Jasa')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <a href="{{ route('services.mine') }}">Jasa Saya</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Tambah Jasa</span>
</div>

<div class="mb-4">
    <h1 class="g-page-title">Tambah Jasa</h1>
    <p class="g-page-sub">Isi detail jasa yang ingin kamu tawarkan.</p>
</div>

<div class="g-panel g-panel-pad g-form-panel">
    <form method="POST" action="{{ route('services.store') }}" enctype="multipart/form-data">
        @include('services._form')
    </form>
</div>
@endsection
