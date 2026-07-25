@extends('layouts.admin')

@section('title', 'Kelola Kategori | Portal Berita Admin')
@section('page_title', 'Kelola Kategori')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form class="d-flex gap-2" method="GET">
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari kategori...">
                    <button class="btn btn-outline-danger">Cari</button>
                </form>
                <a class="btn btn-danger" href="{{ route('admin.categories.create') }}">Tambah Kategori</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Berita</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->posts_count }}</td>
                                <td><span class="badge text-bg-{{ $category->is_active ? 'success' : 'secondary' }}">{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
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

            {{ $categories->links() }}
        </div>
    </div>
@endsection
