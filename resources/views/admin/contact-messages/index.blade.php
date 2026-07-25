@extends('layouts.admin')

@section('title', 'Pesan Masuk | Portal Berita Admin')
@section('page_title', 'Pesan Masuk')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form class="d-flex flex-wrap gap-2" method="GET">
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, subjek..." style="max-width: 280px;">
                    <select class="form-select" name="status" style="max-width: 190px;">
                        <option value="">Semua status</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-danger">Filter</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Subjek</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $message)
                            <tr>
                                <td>{{ $message->nama }}</td>
                                <td>{{ $message->email }}</td>
                                <td>{{ $message->subjek }}</td>
                                <td>
                                    <span class="badge text-bg-{{ $message->status === \App\Models\ContactMessage::STATUS_UNREAD ? 'danger' : 'success' }}">
                                        {{ $message->status }}
                                    </span>
                                </td>
                                <td>{{ $message->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.contact-messages.show', $message) }}">Detail</a>
                                    @if($message->status === \App\Models\ContactMessage::STATUS_UNREAD)
                                        <form class="d-inline" action="{{ route('admin.contact-messages.read', $message) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-success">Sudah Dibaca</button>
                                        </form>
                                    @endif
                                    <form class="d-inline" action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada pesan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $messages->links() }}
        </div>
    </div>
@endsection
