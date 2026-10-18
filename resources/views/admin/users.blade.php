@extends('admin.layout')
@section('title', 'Pengguna')

@section('content')
<div class="g-panel">
    <div class="table-responsive">
        <table class="table g-panel-table mb-0 align-middle">
            <thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th class="text-end">Aksi</th></tr></thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_freelancer)<span class="badge bg-primary me-1">Freelancer</span>@endif
                            @if($user->is_client)<span class="badge bg-secondary">Client</span>@endif
                        </td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                  onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
