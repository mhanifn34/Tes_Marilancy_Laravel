<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Task;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    // Halaman Workspace buat Freelancer & Klien liat progres
    public function index($jobId)
    {
        $job = Job::with('tasks')->findOrFail($jobId);
        
        // Hitung progres (jumlah task 'done' / total task) * 100
        $totalTasks = $job->tasks->count();
        $doneTasks = $job->tasks->where('status', 'done')->count();
        $progress = $totalTasks > 0 ? ($doneTasks / $totalTasks) * 100 : 0;

        return view('workspace.index', compact('job', 'progress'));
    }

    // Tambah list tugas baru (Cuma bisa buat Freelancer yang diterima)
    public function addTask(Request $request, $jobId)
    {
        // Validasi input
        $request->validate([
            'task_name' => 'required|string|max:255',
        ]);

        $job = Job::findOrFail($jobId);

        // Cek apakah user adalah freelancer yang diterima
        $acceptedApplication = $job->applications()->where('status', 'accepted')->first();
        if (!$acceptedApplication || Auth::id() !== $acceptedApplication->freelancer_id) {
            return redirect()->back()->with('error', 'Kamu nggak terlibat di job ini!');
        }

        Task::create([
            'job_id' => $jobId,
            'task_name' => $request->task_name,
            'status' => 'to_do'
        ]);

        return redirect()->back()->with('success', 'Tugas baru berhasil ditambah!');
    }

    // Update status tugas (To Do -> Doing -> Done)
    public function updateTaskStatus(Request $request, $taskId)
    {
        // Validasi status
        $request->validate([
            'status' => 'required|in:to_do,doing,done',
        ]);

        $task = Task::findOrFail($taskId);
        $job = $task->job;

        // Cek akses: client atau freelancer yang diterima
        $acceptedApplication = $job->applications()->where('status', 'accepted')->first();
        if (Auth::id() !== $job->client_id && (!$acceptedApplication || Auth::id() !== $acceptedApplication->freelancer_id)) {
            return redirect()->back()->with('error', 'Kamu nggak terlibat di job ini!');
        }

        $task->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status tugas diperbarui!');
    }
}