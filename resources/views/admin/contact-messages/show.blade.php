@extends('layouts.admin')

@section('title', 'Detail Pesan | Portal Berita Admin')
@section('page_title', 'Detail Pesan')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                <div>
                    <span class="badge text-bg-{{ $message->status === \App\Models\ContactMessage::STATUS_UNREAD ? 'danger' : 'success' }}">
                        {{ $message->status }}
                    </span>
                    <h2 class="h4 mt-3 mb-1">{{ $message->subjek }}</h2>
                    <p class="text-muted mb-0">{{ $message->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <a class="btn btn-outline-secondary align-self-start" href="{{ route('admin.contact-messages.index') }}">Kembali</a>
            </div>

            <dl class="row">
                <dt class="col-sm-2">Nama</dt>
                <dd class="col-sm-10">{{ $message->nama }}</dd>
                <dt class="col-sm-2">Email</dt>
                <dd class="col-sm-10"><a href="mailto:{{ $message->email }}">{{ $message->email }}</a></dd>
                <dt class="col-sm-2">Pesan</dt>
                <dd class="col-sm-10"><p class="mb-0" style="white-space: pre-line;">{{ $message->pesan }}</p></dd>
            </dl>

            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-outline-danger">Hapus Pesan</button>
            </form>
        </div>
    </div>
@endsection
