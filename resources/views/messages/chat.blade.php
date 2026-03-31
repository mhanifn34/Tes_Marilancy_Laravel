@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Chat untuk Job: {{ $job->title }}</h3>
                </div>
                <div class="card-body" style="height: 400px; overflow-y: scroll;">
                    @forelse($messages as $message)
                        <div class="mb-3">
                            <strong>{{ $message->sender->name }}:</strong>
                            <p class="mb-1">{{ $message->message }}</p>
                            <small class="text-muted">{{ $message->created_at->format('d M Y H:i') }}</small>
                        </div>
                    @empty
                        <p class="text-center">Belum ada pesan. Mulai chat yuk!</p>
                    @endforelse
                </div>
                <div class="card-footer">
                    <form action="{{ route('chat.send', $job->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ Auth::id() === $job->client_id ? $job->applications()->where('status', 'accepted')->first()->freelancer_id : $job->client_id }}">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control @error('message') is-invalid @enderror" placeholder="Ketik pesan..." required>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button class="btn btn-primary" type="submit">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection