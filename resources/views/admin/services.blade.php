@extends('admin.layout')
@section('title', 'Jasa')

@section('content')
<div class="g-panel">
    <div class="table-responsive">
        <table class="table g-panel-table mb-0 align-middle">
            <thead><tr><th>Judul</th><th>Freelancer</th><th>Kategori</th><th>Harga</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>{{ $service->title }}</td>
                        <td>{{ $service->user->name }}</td>
                        <td>{{ $service->category->name }}</td>
                        <td>Rp {{ number_format($service->price, 0, ',', '.') }}</td>
                        <td class="text-end">
                            <a href="{{ route('services.show', $service) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus jasa ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada jasa.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $services->links() }}</div>
@endsection