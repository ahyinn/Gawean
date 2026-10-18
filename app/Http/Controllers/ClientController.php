<?php

namespace App\Http\Controllers;

use App\Models\User;

class ClientController extends Controller
{
    // Profil publik client: info dasar saja (tanpa riwayat pesanan)
    public function show(User $user)
    {
        // Akun yang hanya freelancer dan tidak pernah memesan tidak punya profil client
        abort_unless($user->is_client || $user->orders()->exists(), 404);

        return view('clients.show', ['client' => $user]);
    }
}
