<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Tampilin history chat buat job tertentu, cuma yang terlibat aja
    public function showChat($jobId)
    {
        $job = Job::findOrFail($jobId);

        // Cek apakah user ini terlibat di job (client atau freelancer yang diterima)
        $acceptedApplication = $job->applications()->where('status', 'accepted')->first();
        if (Auth::id() !== $job->client_id && (!$acceptedApplication || Auth::id() !== $acceptedApplication->freelancer_id)) {
            return redirect()->back()->with('error', 'Kamu nggak terlibat di job ini, bro!');
        }

        // Ambil semua pesan buat job ini, urutkan berdasarkan waktu
        $messages = Message::where('job_id', $jobId)->with(['sender', 'receiver'])->orderBy('created_at')->get();

        return view('messages.chat', compact('job', 'messages'));
    }

    // Kirim pesan baru, validasi dulu biar aman
    public function sendMessage(Request $request, $jobId)
    {
        // Validasi input
        $request->validate([
            'message' => 'required|string|max:1000',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $job = Job::findOrFail($jobId);

        // Pastikan sender adalah user yang login
        $senderId = Auth::id();

        // Simpan pesan
        Message::create([
            'job_id' => $jobId,
            'sender_id' => $senderId,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Pesan terkirim!');
    }
}
