<?php

namespace App\Http\Controllers;

use App\Models\User;

class FreelancerController extends Controller
{
    // Profil publik freelancer (data asli dari tabel users)
    public function show(User $user)
    {
        abort_unless($user->is_freelancer, 404);

        return view('freelancers.show', [
            'freelancer' => $user,
            'services' => $user->services()->with('category')->latest()->get(),
            'reviews' => $user->reviews()->with('reviewer')->latest()->limit(10)->get(),
        ]);
    }
}
