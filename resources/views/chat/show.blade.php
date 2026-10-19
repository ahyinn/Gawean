@extends('layouts.app')
@section('title', 'Chat dengan '.$other->name)

@section('content')
<div class="g-crumbs mb-3">
    <a href="{{ route('home') }}">Beranda</a>
    <i class="bi bi-chevron-right small"></i>
    <a href="{{ route('chat.index') }}">Chat</a>
    <i class="bi bi-chevron-right small"></i>
    <span class="cur">{{ $other->name }}</span>
</div>

<div class="g-panel g-form-panel" style="max-width:820px">
    <div class="g-chat">
        <div class="g-chat-head">
            <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-primary" aria-label="Kembali ke daftar chat"><i class="bi bi-arrow-left"></i></a>
            <x-avatar :user="$other" :size="40" />
            <div style="min-width:0">
                @if($other->is_freelancer)
                    <a href="{{ route('freelancers.show', $other) }}" class="fw-bold text-decoration-none" style="font-family:var(--g-font-head)">{{ $other->name }}</a>
                @else
                    <a href="{{ route('clients.show', $other) }}" class="fw-bold text-decoration-none" style="font-family:var(--g-font-head)">{{ $other->name }}</a>
                @endif
                <div class="small text-muted">{{ $other->is_freelancer ? 'Freelancer' : 'Pembeli' }}</div>
            </div>
        </div>

        <div class="g-chat-body" id="chatBody" aria-live="polite">
            <div class="g-chat-empty" id="chatEmpty">Belum ada pesan. Sapa {{ $other->name }} duluan!</div>
        </div>

        <form class="g-chat-form" id="chatForm" method="POST" action="{{ route('chat.send', $conversation) }}">
            @csrf
            <textarea name="body" id="chatInput" rows="1" maxlength="2000" class="form-control" placeholder="Tulis pesan..." aria-label="Pesan" required></textarea>
            <button type="submit" class="btn btn-primary" id="chatSend"><i class="bi bi-send-fill"></i><span class="visually-hidden">Kirim</span></button>
        </form>
        <div class="px-3 pb-2 small text-muted">Enter untuk kirim, Shift+Enter untuk baris baru.</div>
        <div class="px-3 pb-2 small text-danger" id="chatError" role="alert" hidden></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var cfg = {
        pollUrl: @json(route('chat.poll', $conversation)),
        sendUrl: @json(route('chat.send', $conversation)),
        csrf: @json(csrf_token()),
        initial: @json($initialMessages),
        interval: 3000
    };

    var body = document.getElementById('chatBody');
    var empty = document.getElementById('chatEmpty');
    var form = document.getElementById('chatForm');
    var input = document.getElementById('chatInput');
    var sendBtn = document.getElementById('chatSend');
    var errBox = document.getElementById('chatError');

    var seen = {};
    var lastId = 0;
    var polling = false;
    var stopped = false;

    // Pesan dirender dengan textContent (BUKAN innerHTML) supaya isi pesan tidak pernah dianggap HTML/script (anti-XSS).
    function render(m) {
        if (seen[m.id]) return;
        seen[m.id] = true;
        if (m.id > lastId) lastId = m.id;
        if (empty) { empty.remove(); empty = null; }

        var el = document.createElement('div');
        el.className = 'g-msg ' + (m.mine ? 'me' : 'them');
        var text = document.createElement('span');
        text.textContent = m.body;
        var time = document.createElement('span');
        time.className = 't';
        time.textContent = m.time;
        el.appendChild(text);
        el.appendChild(time);
        body.appendChild(el);
    }

    function renderAll(list, forceScroll) {
        if (!list.length) return;
        var nearBottom = body.scrollHeight - body.scrollTop - body.clientHeight < 80;
        var hasMine = false;
        list.forEach(function (m) { render(m); if (m.mine) hasMine = true; });
        if (forceScroll || nearBottom || hasMine) body.scrollTop = body.scrollHeight;
    }

    function showError(msg) {
        errBox.textContent = msg || '';
        errBox.hidden = !msg;
    }

    function poll() {
        if (polling || stopped || document.hidden) return;
        polling = true;
        fetch(cfg.pollUrl + '?after=' + lastId, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        }).then(function (r) {
            if (r.status === 401 || r.status === 403 || r.status === 404) { stopped = true; return null; }
            return r.ok ? r.json() : null;
        }).then(function (d) {
            if (!d) return;
            renderAll(d.messages, false);
            if (window.gaweanSetBadge) window.gaweanSetBadge('messages', d.unread_total);
        }).catch(function () {}).then(function () { polling = false; });
    }

    function send() {
        var text = input.value.trim();
        if (!text) return;
        showError('');
        sendBtn.disabled = true;

        fetch(cfg.sendUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': cfg.csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ body: text })
        }).then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (d) { return { status: r.status, data: d }; });
        }).then(function (res) {
            if (res.status === 201) {
                renderAll([res.data.message], true);
                if (window.gaweanChat && window.gaweanChat.onSent) window.gaweanChat.onSent(res.data.message.id);
                input.value = '';
                input.style.height = '';
            } else if (res.status === 422) {
                showError((res.data.errors && res.data.errors.body && res.data.errors.body[0]) || 'Pesan tidak valid.');
            } else if (res.status === 429) {
                showError('Terlalu cepat mengirim pesan. Tunggu sebentar lalu coba lagi.');
            } else {
                showError('Pesan gagal dikirim. Coba lagi.');
            }
        }).catch(function () {
            showError('Koneksi bermasalah. Pesan belum terkirim.');
        }).then(function () {
            sendBtn.disabled = false;
            input.focus();
        });
    }

    form.addEventListener('submit', function (e) { e.preventDefault(); send(); });
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
    });
    input.addEventListener('input', function () {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 140) + 'px';
    });
    document.addEventListener('visibilitychange', function () { if (!document.hidden) poll(); });

    renderAll(cfg.initial, true);
    var timer = setInterval(poll, cfg.interval);

    // Dipakai oleh modul Firebase di bawah
    window.gaweanChat = {
        poll: poll,
        getLastId: function () { return lastId; },
        slowDown: function () { clearInterval(timer); timer = setInterval(poll, 15000); }, // polling jadi cadangan saja
        onSent: null
    };
})();
</script>
<script type="module">
    // Realtime lewat Firebase: tarik pesan baru dari Laravel begitu ada sinyal, tanpa menunggu polling.
    // Kalau Firebase tidak aktif / ditolak rules, chat tetap jalan lewat polling 3 detik.
    const fb = window.gaweanFirebase;
    const chat = window.gaweanChat;
    if (fb && chat) {
        const meId = {{ (int) auth()->id() }};
        const convRef = fb.doc(fb.db, 'chatSignals', 'c_' + {{ (int) $conversation->id }});
        const otherRef = fb.doc(fb.db, 'userSignals', 'u_' + {{ (int) $other->id }});
        let slowed = false;

        fb.onSnapshot(convRef, function (snap) {
            if (!snap.metadata.fromCache && !slowed) { slowed = true; chat.slowDown(); }
            const d = snap.data();
            if (d && d.senderId !== meId && d.lastMessageId > chat.getLastId()) chat.poll();
        }, function (err) {
            console.warn('Firebase realtime tidak aktif, chat memakai polling:', err.code);
        });

        chat.onSent = function (messageId) {
            const payload = { lastMessageId: messageId, senderId: meId, ts: fb.serverTimestamp() };
            fb.setDoc(convRef, payload).catch(function () {});
            fb.setDoc(otherRef, payload).catch(function () {});
        };
    }
</script>
@endpush
