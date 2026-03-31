<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // List semua lowongan buat Freelancer cari cuan, dengan fitur search dan filter
    public function index(Request $request)
    {
        // Query dasar: hanya job yang statusnya 'open'
        $query = Job::where('status', 'open');

        // Filter berdasarkan search (judul) jika ada
        $query->when($request->search, function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%');
        });

        // Filter berdasarkan category jika ada
        $query->when($request->category, function ($q) use ($request) {
            $q->where('category', $request->category);
        });

        // Ambil data dengan urutan terbaru
        $jobs = $query->latest()->get();

        return view('jobs.index', compact('jobs'));
    }

    // Simpan lowongan baru dari Klien
    public function store(Request $request)
    {
        // Validasi simpel ala mahasiswa Telkom biar aman dari input aneh
        $request->validate([
            'title' => 'required|string|max:255',
            'budget' => 'required|numeric|min:10000',
        ]);

        Job::create([
            'client_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'budget' => $request->budget,
        ]);

        return redirect()->back()->with('success', 'Lowongan berhasil diposting!');
    }
}
