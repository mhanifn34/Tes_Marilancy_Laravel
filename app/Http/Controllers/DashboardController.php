<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role == 'freelancer') {
            // Data buat Freelancer
            $stats = [
                'active_jobs' => Job::where('status', 'on_progress')
                                    ->whereHas('applications', fn($q) => $q->where('freelancer_id', $user->id)->where('status', 'accepted'))
                                    ->count(),
                'total_earnings' => Job::where('status', 'closed')
                                    ->whereHas('applications', fn($q) => $q->where('freelancer_id', $user->id)->where('status', 'accepted'))
                                    ->sum('budget'),
            ];
        } else {
            // Data buat Klien
            $stats = [
                'my_postings' => Job::where('client_id', $user->id)->count(),
                'active_projects' => Job::where('client_id', $user->id)->where('status', 'on_progress')->count(),
            ];
        }

        return view('dashboard', compact('stats'));
    }
}
