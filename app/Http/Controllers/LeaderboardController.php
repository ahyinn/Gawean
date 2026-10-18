<?php

namespace App\Http\Controllers;

use App\Models\User;

class LeaderboardController extends Controller
{
    public function index()
    {
        return view('leaderboard.index', ['topSellers' => User::topSellers(10)]);
    }
}
