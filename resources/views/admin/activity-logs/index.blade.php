@extends('layouts.admin')

@section('title', 'Activity Log | Portal Berita Admin')
@section('page_title', 'Activity Log')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form class="row g-2 mb-4" method="GET">
                <div class="col-md-2">
                    <input class="form-control" type="date" name="date" value="{{ request('date') }}">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="user_id">
                        <option value="">Semua user</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="role">
                        <option value="">Semua role</option>
                        @foreach($roles as $role => $label)
                            <option value="{{ $role }}" @selected(request('role') === $role)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="module">
                        <option value="">Semua modul</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" @selected(request('module') === $module)>{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="action">
                        <option value="">Semua aksi</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" @selected(request('action') === $action)>{{ ucfirst(str_replace('-', ' ', $action)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari log...">
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-danger">Filter</button>
                    <a class="btn btn-outline-secondary" href="{{ route('admin.activity-logs.index') }}">Reset</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Role</th>
                            <th>Modul</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $log->user?->name ?? 'System' }}</td>
                                <td>
                                    <span class="badge text-bg-dark">{{ $log->user?->role ?? '-' }}</span>
                                </td>
                                <td>{{ ucfirst($log->module) }}</td>
                                <td>
                                    <span class="badge text-bg-secondary">{{ ucfirst(str_replace('-', ' ', $log->action)) }}</span>
                                </td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada activity log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}
        </div>
    </div>
@endsection
