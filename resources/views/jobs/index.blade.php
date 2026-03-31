@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Cari Lowongan Kerja Digital</h2>

    <form action="{{ route('jobs.index') }}" method="GET" class="mb-4">
        <div class="row g-2">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Cari judul pekerjaan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="Desain Grafis" {{ request('category') == 'Desain Grafis' ? 'selected' : '' }}>Desain Grafis</option>
                    <option value="Pemrograman" {{ request('category') == 'Pemrograman' ? 'selected' : '' }}>Pemrograman</option>
                    <option value="Content Writer" {{ request('category') == 'Content Writer' ? 'selected' : '' }}>Content Writer</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Cari</button>
            </div>
        </div>
    </form>

    <div class="row">
        @foreach($jobs as $job)
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body">
                    <h5 class="card-title font-weight-bold">{{ $job->title }}</h5>
                    <p class="text-muted small">Kategori: {{ $job->category }} | Budget: Rp{{ number_format($job->budget) }}</p>
                    <p>{{ Str::limit($job->description, 120) }}</p>
                    
                    @if(Auth::check() && Auth::user()->role == 'freelancer')
                        <hr>
                        <form action="{{ route('jobs.apply', $job->id) }}" method="POST">
                            @csrf
                            <div class="mb-2">
                                <label class="small font-weight-bold">Tawaran Harga (Bidding):</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="bid_amount" class="form-control" required>
                                </div>
                            </div>
                            <textarea name="proposal" class="form-control form-control-sm mb-2" placeholder="Kenapa anda layak?" required></textarea>
                            <button type="submit" class="btn btn-success btn-sm w-100">Kirim Lamaran</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection