<a href="{{ route('chat.index') }}" class="g-nav-icon {{ request()->routeIs('chat.*') ? 'active' : '' }}" aria-label="Chat" title="Chat">
    <i class="bi bi-chat-dots"></i>
    <span class="g-badge" data-badge="messages" @if($navUnreadMessages < 1) hidden @endif>{{ $navUnreadMessages > 99 ? '99+' : $navUnreadMessages }}</span>
</a>
<a href="{{ route('notifications.index') }}" class="g-nav-icon {{ request()->routeIs('notifications.*') ? 'active' : '' }}" aria-label="Notifikasi" title="Notifikasi">
    <i class="bi bi-bell"></i>
    <span class="g-badge" data-badge="notifications" @if($navUnreadNotifications < 1) hidden @endif>{{ $navUnreadNotifications > 99 ? '99+' : $navUnreadNotifications }}</span>
</a>
