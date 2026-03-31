@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Kelola Profil & Portofolio (Biar Klien Tertarik!)</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Bio Singkat</label>
                            <textarea name="bio" class="form-control" rows="3">{{ Auth::user()->bio }}</textarea>
                            <small class="text-muted">Jelasin pengalaman kamu ala mahasiswa Telkom yang ambis.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Skill / Keahlian</label>
                            <input type="text" name="skills" class="form-control" value="{{ Auth::user()->skills }}" placeholder="Contoh: Laravel, Figma, Copywriting">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Link Portofolio (GitHub/Behance)</label>
                            <input type="url" name="portfolio_link" class="form-control" value="{{ Auth::user()->portfolio_link }}">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection