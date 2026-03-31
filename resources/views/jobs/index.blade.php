@foreach($jobs as $job)
<div class="card mb-3 shadow-sm">
    <div class="card-body">
        <h5 class="card-title">{{ $job->title }}</h5>
        <p class="text-muted">Kategori: {{ $job->category }} | Budget: Rp{{ number_format($job->budget) }}</p>
        <p>{{ Str::limit($job->description, 150) }}</p>
        
        @if(Auth::check() && Auth::user()->role == 'freelancer')
            <form action="{{ route('jobs.apply', $job->id) }}" method="POST" class="mt-3">
                @csrf
                <div class="input-group mb-2">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="bid_amount" class="form-control" placeholder="Harga tawaranmu" required>
                </div>
                <textarea name="proposal" class="form-control mb-2" placeholder="Kenapa kamu cocok buat proyek ini?" required></textarea>
                <button type="submit" class="btn btn-primary btn-sm">Kirim Lamaran</button>
            </form>
        @endif
    </div>
</div>
@endforeach