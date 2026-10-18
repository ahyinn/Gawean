{{-- Tombol "Chat": POST /chat/mulai/{user}. Hanya tampil kalau aturan chat mengizinkan
     (ke freelancer, atau ke pembeli dari jasa sendiri). Tamu diarahkan ke modal login. --}}
@props(['target', 'label' => 'Chat', 'class' => 'btn btn-outline-primary'])
@auth
    @if(auth()->user()->canStartChatWith($target))
        <form method="POST" action="{{ route('chat.start', $target) }}" class="d-inline">
            @csrf
            <button type="submit" class="{{ $class }}"><i class="bi bi-chat-dots me-1"></i> {{ $label }}</button>
        </form>
    @endif
@else
    <button type="button" class="{{ $class }}" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="bi bi-chat-dots me-1"></i> {{ $label }}</button>
@endauth
