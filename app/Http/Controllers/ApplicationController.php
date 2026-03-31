<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ApplicationController extends Controller
{
    // Handle Freelancer yang mau ngebid proyek
    public function apply(Request $request, $jobId)
    {
        // Validasi input biar aman
        $request->validate([
            'bid_amount' => 'required|numeric|min:0',
            'proposal' => 'required|string|max:1000',
        ]);

        // Cek dulu, jangan sampe Klien ngebid kerjaan sendiri
        if (Auth::user()->role !== 'freelancer') {
            return redirect()->back()->with('error', 'Cuma Freelancer yang bisa melamar, Bos!');
        }

        Application::create([
            'job_id' => $jobId,
            'freelancer_id' => Auth::id(),
            'bid_amount' => $request->bid_amount,
            'proposal' => $request->proposal,
        ]);

        return redirect()->route('jobs.index')->with('success', 'Lamaran berhasil dikirim. Semoga tembus!');
    }

    // Accept lamaran: update status, reject yang lain, ubah job status, catat transaksi escrow
    public function accept($id)
    {
        $application = Application::findOrFail($id);
        $job = $application->job;

        // Pastikan hanya client yang bisa accept
        if (Auth::id() !== $job->client_id) {
            return redirect()->back()->with('error', 'Hanya client yang bisa menerima lamaran!');
        }

        // Update status application jadi accepted
        $application->update(['status' => 'accepted']);

        // Reject semua application lain di job yang sama
        Application::where('job_id', $job->id)->where('id', '!=', $id)->update(['status' => 'rejected']);

        // Update status job jadi on_progress
        $job->update(['status' => 'on_progress']);

        // Catat transaksi escrow: client bayar ke sistem (expense untuk client, tapi simulasi)
        // Asumsi escrow adalah amount dari bid_amount
        Transaction::create([
            'user_id' => $job->client_id, // Client yang bayar
            'job_id' => $job->id,
            'amount' => $application->bid_amount,
            'type' => 'expense', // Expense untuk client
        ]);

        // Kirim notifikasi ke freelancer yang diterima
        $freelancer = $application->freelancer; // Asumsi ada relasi di Application
        Notification::send($freelancer, new \App\Notifications\ApplicationAccepted($job));

        return redirect()->back()->with('success', 'Pelamar diterima! Job dimulai.');
    }

    // Reject lamaran: update status jadi rejected
    public function reject($id)
    {
        $application = Application::findOrFail($id);
        $job = $application->job;

        // Pastikan hanya client yang bisa reject
        if (Auth::id() !== $job->client_id) {
            return redirect()->back()->with('error', 'Hanya client yang bisa menolak lamaran!');
        }

        // Update status jadi rejected
        $application->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Lamaran ditolak.');
    }
}
