<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Support\Waktu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private const HISTORY_LIMIT = 100;

    // Kotak masuk: semua percakapan saya, terbaru di atas
    public function index(Request $request)
    {
        $me = $request->user();

        $conversations = Conversation::forUser($me)
            ->with(['userOne', 'userTwo', 'latestMessage'])
            ->withCount(['messages as unread_count' => fn ($q) => $q
                ->whereNull('read_at')
                ->where('sender_id', '!=', $me->id)])
            ->latest('updated_at')
            ->get();

        return view('chat.index', compact('conversations'));
    }

    // Mulai (atau buka lagi) percakapan dengan user lain
    public function start(Request $request, User $user)
    {
        $me = $request->user();

        abort_if($user->id === $me->id, 403, 'Kamu tidak bisa chat dengan dirimu sendiri.');
        abort_unless($me->canStartChatWith($user), 403, 'Chat hanya bisa dimulai ke freelancer, atau ke pembeli dari jasamu sendiri.');

        return redirect()->route('chat.show', Conversation::between($me, $user));
    }

    // Halaman percakapan. Pesan dirender oleh JS dari JSON (textContent), bukan HTML.
    public function show(Request $request, Conversation $conversation)
    {
        $me = $request->user();
        abort_unless($conversation->isParticipant($me), 403);

        $this->markAsRead($conversation, $me);

        $messages = $conversation->messages()
            ->latest('id')->limit(self::HISTORY_LIMIT)->get()
            ->reverse()->values()
            ->map(fn (Message $m) => $this->present($m, $me));

        return view('chat.show', [
            'conversation' => $conversation,
            'other' => $conversation->load(['userOne', 'userTwo'])->otherUser($me),
            'initialMessages' => $messages,
        ]);
    }

    // Polling: pesan baru setelah id tertentu (GET /chat/{id}/pesan?after=123)
    public function poll(Request $request, Conversation $conversation): JsonResponse
    {
        $me = $request->user();
        abort_unless($conversation->isParticipant($me), 403);

        $after = max(0, (int) $request->query('after', 0));

        $messages = $conversation->messages()
            ->where('id', '>', $after)
            ->orderBy('id')->limit(self::HISTORY_LIMIT)->get();

        $this->markAsRead($conversation, $me);

        return response()->json([
            'messages' => $messages->map(fn (Message $m) => $this->present($m, $me))->values(),
            'unread_total' => $me->unreadMessagesCount(),
        ]);
    }

    // Kirim pesan (maks. 2000 karakter; di route sudah dipasang throttle)
    public function send(Request $request, Conversation $conversation)
    {
        $me = $request->user();
        abort_unless($conversation->isParticipant($me), 403);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ], [
            'body.required' => 'Pesan tidak boleh kosong.',
            'body.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $me->id,
            'body' => $data['body'],
        ]);

        $conversation->touch(); // supaya naik ke atas di kotak masuk

        if ($request->expectsJson()) {
            return response()->json(['message' => $this->present($message, $me)], 201);
        }

        return redirect()->route('chat.show', $conversation);
    }

    // Tandai pesan dari lawan bicara sebagai terbaca
    private function markAsRead(Conversation $conversation, User $me): void
    {
        $conversation->messages()
            ->where('sender_id', '!=', $me->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    private function present(Message $message, User $me): array
    {
        return [
            'id' => $message->id,
            'body' => $message->body,
            'mine' => $message->sender_id === $me->id,
            'time' => Waktu::chat($message->created_at),
        ];
    }
}
