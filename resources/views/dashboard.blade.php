@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white font-weight-bold">
                    🚀 Dashboard MariLancy - {{ Auth::user()->role == 'client' ? 'Klien' : 'Freelancer' }}
                </div>

                <div class="card-body text-center">
                    <h4 class="mb-4">{{ $stats['msg'] }}</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="p-4 bg-light rounded">
                                <h5>Proyek Aktif</h5>
                                <h2 class="text-primary">{{ $stats['active'] }}</h2>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="p-4 bg-light rounded">
                                <h5>{{ Auth::user()->role == 'client' ? 'Total Postingan' : 'Estimasi Cuan' }}</h5>
                                <h2 class="text-success">
                                    {{ Auth::user()->role == 'client' ? ($stats['total_post'] ?? 0) : 'Rp' . number_format($stats['earnings'] ?? 0) }}
                                </h2>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">Lihat Daftar Proyek</a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary">Update Portofolio</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection