<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil.
     * Tugas PPL: Pastikan user sudah login lewat middleware di route.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update data profil ke tabel users.
     * Kita pake validasi standar biar nilai Tubes aman jaya.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input dari form
        $request->validate([
            'bio' => 'nullable|string|max:500',
            'skills' => 'nullable|string|max:255',
            'portfolio_link' => 'nullable|url', // Cek apakah formatnya beneran link (http/https)
        ]);

        // Proses update data ke database
        // @var User $user
        $user->update([
            'bio' => $request->bio,
            'skills' => $request->skills,
            'portfolio_link' => $request->portfolio_link,
        ]);

        // Balikin ke halaman edit dengan pesan sukses (Success Toast/Alert)
        return redirect()->route('profile.edit')->with('success', 'Profil MariLancy kamu berhasil diperbarui!');
    }
}