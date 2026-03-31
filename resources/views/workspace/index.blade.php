<div class="container">
    <h2>Workspace: {{ $job->title }}</h2>
    
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
                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                    <option value="to_do" {{ $task->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                    <option value="doing" {{ $task->status == 'doing' ? 'selected' : '' }}>Doing</option>
                    <option value="done" {{ $task->status == 'done' ? 'selected' : '' }}>Done</option>
                </select>
            </form>
        </li>
        @endforeach
    </ul>

   {{-- Cuma muncul buat Klien kalau progres sudah 100% --}}
@if(Auth::user()->role == 'client' && $progress == 100)
<div class="card mt-5 border-warning">
    <div class="card-body">
        <h5 class="card-title text-warning font-weight-bold">Kasih Rating Buat Freelancer-mu!</h5>
        <form action="{{ route('reviews.store', $job->id) }}" method="POST">
            @csrf
            <input type="hidden" name="freelancer_id" value="{{ $job->applications()->where('status', 'accepted')->first()->freelancer_id }}">
            
            <div class="mb-3">
                <label class="form-label">Berapa Bintang? (1-5)</label>
                <select name="rating" class="form-select shadow-none" required>
                    <option value="5">⭐⭐⭐⭐⭐ (Keren Abis)</option>
                    <option value="4">⭐⭐⭐⭐ (Bagus Banget)</option>
                    <option value="3">⭐⭐⭐ (Biasa Aja)</option>
                    <option value="2">⭐⭐ (Perlu Belajar Lagi)</option>
                    <option value="1">⭐ (Parah Sih Ini)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Komentar/Testimoni</label>
                <textarea name="comment" class="form-control" placeholder="Tulis review jujurmu di sini..."></textarea>
            </div>

            <button type="submit" class="btn btn-warning w-100">Kirim Review & Selesaikan Proyek</button>
        </form>
    </div>
</div>
@endif