<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Job;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard admin: lihat semua user dan job buat monitoring
    public function index()
    {
        $users = User::all(); // Semua user
        $jobs = Job::with('client')->get(); // Semua job dengan relasi client

        return view('admin.dashboard', compact('users', 'jobs'));
    }

    // Delete job buat moderasi konten yang melanggar
    public function deleteJob($id)
    {
        $job = Job::findOrFail($id);
        $job->delete(); // Hapus job beserta relasi (cascade)

        return redirect()->back()->with('success', 'Job berhasil dihapus untuk moderasi.');
    }
}
