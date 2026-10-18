<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        $user->name = $data['name'];
        $user->bio = $data['bio'] ?? null;

        $jurusan = $data['jurusan'] ?? null;
        $kampus = $data['kampus'] ?? null;
        $user->campus = ($jurusan && $kampus) ? $jurusan.' · '.$kampus : null;

        if ($user->is_freelancer) {
            $user->skills = ! empty($data['skills']) ? array_values($data['skills']) : null;
        }

        if ($request->hasFile('photo')) {
            $newPath = $request->file('photo')->store('avatars', 'public');

            if ($user->photo) {
                Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $newPath;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function destroyPhoto(Request $request)
    {
        $user = $request->user();

        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
            $user->photo = null;
            $user->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Foto profil dihapus.');
    }
}
