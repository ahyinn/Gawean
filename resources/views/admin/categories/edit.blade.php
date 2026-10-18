@extends('admin.layout')
@section('title', 'Edit Kategori')

@section('content')
<div class="g-panel p-4" style="max-width:520px">
    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label small">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label small">Ikon (kelas Bootstrap Icons)</label>
            <input type="text" name="icon" value="{{ old('icon', $category->icon) }}" class="form-control">
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-link">Batal</a>
    </form>
</div>
@endsection