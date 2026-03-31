@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white font-weight-bold">
            <h2>Workspace: {{ $job->title }}</h2>
        </div>
        <div class="card-body">
            <div class="progress mb-4" style="height: 30px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%">
                    Progres: {{ number_format($progress) }}%
                </div>
            </div>

            @if(Auth::user()->role == 'freelancer')
            <form action="{{ route('workspace.add_task', $job->id) }}" method="POST" class="mb-3">
                @csrf
                <div class="input-group">
                    <input type="text" name="task_name" class="form-control" placeholder="Nama tugas baru..." required>
                    <button class="btn btn-primary">Tambah Task</button>
                </div>
            </form>
            @endif

            <ul class="list-group">
                @foreach($job->tasks as $task)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $task->task_name }}
                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="form-select form-select-sm shadow-none">
                            <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                            <option value="doing" {{ $task->status == 'doing' ? 'selected' : '' }}>Doing</option>
                            <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </form>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection