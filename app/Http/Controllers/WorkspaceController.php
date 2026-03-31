<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function index($jobId)
    {
        $job = Job::with('tasks')->findOrFail($jobId);
        
        $totalTasks = $job->tasks->count();
        // Perbaikan: Pastikan menggunakan koma (,) bukan titik dua (:)
        $doneTasks = $job->tasks->where('status', 'done')->count();
        $progress = $totalTasks > 0 ? ($doneTasks / $totalTasks) * 100 : 0;

        return view('workspace.index', compact('job', 'progress'));
    }

    public function addTask(Request $request, $jobId)
    {
        $request->validate(['task_name' => 'required|string|max:255']);

        $job = Job::findOrFail($jobId);
        $acceptedApp = $job->applications()->where('status', 'accepted')->first();

        if (!$acceptedApp || Auth::id() !== $acceptedApp->freelancer_id) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        Task::create([
            'job_id' => $jobId,
            'task_name' => $request->task_name,
            'status' => 'to_do'
        ]);

        return redirect()->back()->with('success', 'Tugas berhasil ditambah!');
    }

    public function updateTaskStatus(Request $request, $taskId)
    {
        $request->validate(['status' => 'required|in:to_do,doing,done']);

        $task = Task::findOrFail($taskId);
        $job = $task->job;
        $acceptedApp = $job->applications()->where('status', 'accepted')->first();

        // Cek akses: hanya klien pemilik atau freelancer terpilih
        if (Auth::id() !== $job->client_id && (!$acceptedApp || Auth::id() !== $acceptedApp->freelancer_id)) {
            return redirect()->back()->with('error', 'Akses ditolak!');
        }

        $task->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status diperbarui!');
    }
}