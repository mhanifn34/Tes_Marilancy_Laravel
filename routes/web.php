<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - MariLancy Project (Tugas PPL 2026)
|--------------------------------------------------------------------------
*/

// Halaman Landing / List Lowongan buat publik/freelancer
Route::get('/', [JobController::class, 'index'])->name('jobs.index');

// Route Auth Bawaan (Login, Register, Logout)
Auth::routes();
Route::get('/profile/edit', function() { return view('profile.edit'); })->name('profile.edit');
Route::patch('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

// Semua fitur di bawah ini wajib LOGIN dulu
Route::middleware(['auth'])->group(function () {
    
    // 1. Fitur Dashboard Utama
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Fitur Lowongan (Klien)
    Route::post('/jobs/store', [JobController::class, 'store'])->name('jobs.store');
    
    // 3. Fitur Lamaran/Bidding (Freelancer)
    Route::post('/jobs/{id}/apply', [ApplicationController::class, 'apply'])->name('jobs.apply');
    
    // 4. Fitur Workspace & To-Do List (Manajemen Proyek)
    Route::get('/workspace/{jobId}', [WorkspaceController::class, 'index'])->name('workspace.index');
    Route::post('/workspace/{jobId}/add-task', [WorkspaceController::class, 'addTask'])->name('workspace.add_task');
    Route::patch('/tasks/{taskId}/update', [WorkspaceController::class, 'updateTaskStatus'])->name('tasks.update');

    // 5. Fitur Selesai Proyek & Konfirmasi (Klien)
    Route::post('/jobs/{jobId}/complete', function($jobId) {
        $job = \App\Models\Job::findOrFail($jobId);
        $job->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Mantap! Proyek berhasil diselesaikan.');
    })->name('jobs.complete');

    // Review Fitur
Route::post('/jobs/{jobId}/review', function(Illuminate\Http\Request $request, $jobId) {
    $job = \App\Models\Job::findOrFail($jobId);
    \App\Models\Review::create([
        'job_id' => $jobId,
        'freelancer_id' => $job->applications()->where('status', 'accepted')->first()->freelancer_id,
        'rating' => $request->rating,
        'comment' => $request->comment
    ]);
    return redirect()->back()->with('success', 'Review terkirim!');
})->name('reviews.store');

    // Fitur Seleksi Pelamar (Klien)
    Route::patch('/applications/{id}/accept', [ApplicationController::class, 'accept'])->name('applications.accept');
    Route::patch('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');

    // Fitur Wallet (Riwayat Transaksi)
    Route::get('/wallet', function() {
        $transactions = Auth::user()->transactions()->with('job')->latest()->get();
        return view('wallet.index', compact('transactions'));
    })->name('wallet.index');

    // Fitur Chat Internal
    Route::get('/chat/{jobId}', [MessageController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/{jobId}/send', [MessageController::class, 'sendMessage'])->name('chat.send');

});

// Route Admin: cuma bisa diakses admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admin/jobs/{id}', [AdminController::class, 'deleteJob'])->name('admin.delete_job');
});