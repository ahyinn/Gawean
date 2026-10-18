{{-- $users: hasil User::topSellers() (punya ->rank dan ->completed_orders_count). Tidak menampilkan pendapatan. --}}
@foreach($users as $u)
    <div class="g-lb-row">
        <span class="g-rank {{ $u->rank <= 3 ? 'top' : '' }}" title="Peringkat {{ $u->rank }}">{{ $u->rank }}</span>
        <x-avatar :user="$u" :size="44" />
        <div class="who">
            <a href="{{ route('freelancers.show', $u) }}">{{ $u->name }}</a>
            @if($u->campus)
                <div class="sub">{{ $u->campus }}</div>
            @endif
        </div>
        <div class="score">
            <strong>{{ $u->completed_orders_count }}</strong>
            <small>pesanan selesai</small>
        </div>
    </div>
@endforeach
