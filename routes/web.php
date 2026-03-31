<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Models\Review;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes - MariLancy Project
|--------------------------------------------------------------------------
*/

// Halaman Landing / List Lowongan
Route::get('/', [JobController::class, 'index'])->name('jobs.index');

// Route Auth Bawaan (Login, Register, Logout)
Auth::routes();

// Semua fitur di bawah ini wajib LOGIN dulu
Route::middleware(['auth'])->group(function () {
    
    // 1. Fitur Dashboard Utama
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Fitur Profil
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // 3. Fitur Lowongan (Klien)
    Route::post('/jobs/store', [JobController::class, 'store'])->name('jobs.store');
    
    // 4. Fitur Lamaran/Bidding (Freelancer)
    Route::post('/jobs/{id}/apply', [ApplicationController::class, 'apply'])->name('jobs.apply');
    
    // 5. Fitur Workspace & To-Do List (Manajemen Proyek)
    Route::get('/workspace/{jobId}', [WorkspaceController::class, 'index'])->name('workspace.index');
    Route::post('/workspace/{jobId}/add-task', [WorkspaceController::class, 'addTask'])->name('workspace.add_task');
    Route::patch('/tasks/{taskId}/update', [WorkspaceController::class, 'updateTaskStatus'])->name('tasks.update');

    // 6. Fitur Selesai Proyek (Klien)
    Route::post('/jobs/{jobId}/complete', function($jobId) {
        Job::findOrFail($jobId)->update(['status' => 'closed']);
        return redirect()->back()->with('success', 'Mantap! Proyek berhasil diselesaikan.');
    })->name('jobs.complete');

    // 7. Fitur Review
    Route::post('/jobs/{jobId}/review', function(Request $request, $jobId) {
        $job = Job::findOrFail($jobId);
        Review::create([
            'job_id' => $jobId,
            'freelancer_id' => $job->applications()->where('status', 'accepted')->first()->freelancer_id,
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);
        return redirect()->back()->with('success', 'Review terkirim!');
    })->name('reviews.store');

    // 8. Fitur Wallet (Riwayat Transaksi)
    Route::get('/wallet', function() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $transactions = $user->transactions()->with('job')->latest()->get();
        return view('wallet.index', compact('transactions'));
    })->name('wallet.index');

    // 9. Fitur Chat Internal
    Route::get('/chat/{jobId}', [MessageController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/{jobId}/send', [MessageController::class, 'sendMessage'])->name('chat.send');

    // 10. Fitur Seleksi Pelamar (Klien)
    Route::patch('/applications/{id}/accept', [ApplicationController::class, 'accept'])->name('applications.accept');
    Route::patch('/applications/{id}/reject', [ApplicationController::class, 'reject'])->name('applications.reject');
});

// Route Khusus Admin
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admin/jobs/{id}', [AdminController::class, 'deleteJob'])->name('admin.delete_job');
});