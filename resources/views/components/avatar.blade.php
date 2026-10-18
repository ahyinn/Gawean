{{-- Avatar: foto kalau ada, kalau tidak inisial. Pemakaian: <x-avatar :user="$user" :size="44" />
     Inisial selalu dirender di bawah foto, jadi kalau file foto gagal dimuat
     (mis. `php artisan storage:link` belum dijalankan) yang terlihat tetap inisial. --}}
@props(['user', 'size' => 40])
<span {{ $attributes->merge(['class' => 'g-fm-avatar', 'style' => 'width:'.$size.'px;height:'.$size.'px;font-size:'.round($size * 0.38, 1).'px']) }}>
    {{ $user->initials }}
    @if($user->avatar_url)
        <img src="{{ $user->avatar_url }}" alt="" loading="lazy" class="g-ava-img" onerror="this.remove()">
    @endif
    {{ $slot }}
</span>
