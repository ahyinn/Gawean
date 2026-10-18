@extends('admin.layout')
@section('title', 'Kategori')

@section('content')
<div class="g-panel p-4 mb-4">
    <h5 class="fw-bold mb-3">Tambah Kategori</h5>
    <form method="POST" action="{{ route('admin.categories.store') }}" class="row g-2 align-items-end mb-3">
        @csrf
        <div class="col-12 col-md-5 position-relative">
            <label class="form-label small">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback position-absolute">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 col-md-5">
            <label class="form-label small">Ikon (kelas Bootstrap Icons, contoh: bi-palette)</label>
            <input type="text" name="icon" value="{{ old('icon') }}" class="form-control" placeholder="bi-briefcase">
        </div>
        <div class="col-12 col-md-2">
            <button class="btn btn-primary w-100">Tambah</button>
        </div>
    </form>
</div>

<div class="g-panel">
    <div class="table-responsive">
        <table class="table g-panel-table mb-0 align-middle">
            <thead><tr><th>Ikon</th><th>Nama</th><th>Slug</th><th>Jumlah Jasa</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><i class="bi {{ $category->icon }} fs-5 text-primary"></i></td>
                        <td>{{ $category->name }}</td>
                        <td class="text-muted small">{{ $category->slug }}</td>
                        <td>{{ $category->services_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada kategori.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection