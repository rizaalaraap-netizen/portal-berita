@extends('layouts.admin')

@section('title', 'Kelola Admin | Portal Berita Admin')
@section('page_title', 'Kelola Admin/User')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form class="d-flex gap-2" method="GET">
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari admin...">
                    <button class="btn btn-outline-danger">Cari</button>
                </form>
                <a class="btn btn-danger" href="{{ route('admin.users.create') }}">Tambah Admin</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge text-bg-dark">{{ $user->role }}</span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus admin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $users->links() }}
        </div>
    </div>
@endsection
