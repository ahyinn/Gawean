@extends('layouts.app')
@section('title', 'Edit Jasa')

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <a href="{{ route('services.mine') }}">Jasa Saya</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">Edit Jasa</span>
</div>

<div class="mb-4">
    <h1 class="g-page-title">Edit Jasa</h1>
    <p class="g-page-sub">Perbarui detail jasamu.</p>
</div>

<div class="g-panel g-panel-pad g-form-panel">
    <form method="POST" action="{{ route('services.update', $service) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('services._form')
    </form>
</div>
@endsection
