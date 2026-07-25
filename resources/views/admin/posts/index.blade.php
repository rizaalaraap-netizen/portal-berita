@extends('layouts.admin')

@section('title', 'Kelola Berita | Portal Berita Admin')
@section('page_title', 'Kelola Berita')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
                <form id="postFilterForm" class="d-flex flex-wrap gap-2" method="GET">
                    <input class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari judul..." data-realtime-search style="max-width: 220px;">
                    <select class="form-select" name="status">
                        <option value="">Semua status</option>
                        @foreach($statuses as $status => $label)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $label }}</option>
                        @endforeach
                        <option value="trashed" @selected(request('status') === 'trashed')>Terhapus</option>
                    </select>
                    <select class="form-select" name="category_id">
                        <option value="">Semua kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-select" name="author_id">
                        <option value="">Semua penulis</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" @selected(request('author_id') == $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                    <select class="form-select" name="sort">
                        <option value="latest" @selected(request('sort', 'latest') === 'latest')>Terbaru</option>
                        <option value="oldest" @selected(request('sort') === 'oldest')>Terlama</option>
                    </select>
                    <button class="btn btn-outline-danger">Filter</button>
                </form>
                <div class="d-flex gap-2">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.posts.trash') }}">Trash</a>
                    <a class="btn btn-danger" href="{{ route('admin.posts.create') }}">Tambah Berita</a>
                </div>
            </div>

            @unless($isTrash)
                <form id="bulkForm" action="{{ route('admin.posts.bulk') }}" method="POST" class="d-flex flex-wrap gap-2 align-items-center mb-3">
                    @csrf
                    <select class="form-select" name="action" style="max-width: 180px;" required>
                        <option value="">Bulk Action</option>
                        @if(auth()->user()->canPublishPosts())
                            <option value="publish">Publish</option>
                        @endif
                        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                            <option value="archive">Archive</option>
                        @endif
                        <option value="draft">Draft</option>
                        <option value="delete">Soft Delete</option>
                    </select>
                    <button class="btn btn-outline-danger" onclick="return confirm('Proses berita yang dipilih?')">Terapkan</button>
                </form>
            @endunless

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            @unless($isTrash)
                                <th style="width: 40px;"><input type="checkbox" class="form-check-input" data-check-all></th>
                            @endunless
                            <th>Thumbnail</th>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Penulis</th>
                            <th>Status</th>
                            <th>Publish</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                            <tr>
                                @unless($isTrash)
                                    <td><input type="checkbox" class="form-check-input" name="post_ids[]" value="{{ $post->id }}" form="bulkForm" data-row-check></td>
                                @endunless
                                <td><img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" style="width: 88px; height: 58px; object-fit: cover; border-radius: .5rem;"></td>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->category->name }}</td>
                                <td>{{ $post->author->name }}</td>
                                <td><span class="badge text-bg-{{ $post->status_badge }}">{{ $post->status_label }}</span></td>
                                <td>{{ $post->published_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.posts.preview', $post) }}" target="_blank">Preview</a>

                                    @if($post->trashed())
                                        <form action="{{ route('admin.posts.restore', $post) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-outline-success">Restore</button>
                                        </form>
                                        <form action="{{ route('admin.posts.force-delete', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen berita ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Force Delete</button>
                                        </form>
                                    @else
                                        @can('update', $post)
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.posts.edit', $post) }}">Edit</a>
                                        @endcan
                                        @can('submitReview', $post)
                                            <form action="{{ route('admin.posts.submit-review', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-warning">Kirim Review</button>
                                            </form>
                                        @endcan
                                        @can('approve', $post)
                                            <form action="{{ route('admin.posts.approve', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success">Approve</button>
                                            </form>
                                        @endcan
                                        @can('returnToDraft', $post)
                                            <form action="{{ route('admin.posts.return-draft', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-secondary">Draft</button>
                                            </form>
                                        @endcan
                                        @can('publish', $post)
                                            <form action="{{ route('admin.posts.publish', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-success">Publish</button>
                                            </form>
                                        @endcan
                                        @can('unpublish', $post)
                                            <form action="{{ route('admin.posts.unpublish', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-secondary">Unpublish</button>
                                            </form>
                                        @endcan
                                        @can('archive', $post)
                                            <form action="{{ route('admin.posts.archive', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Arsipkan berita ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-danger">Archive</button>
                                            </form>
                                        @endcan
                                        @can('restoreArchived', $post)
                                            <form action="{{ route('admin.posts.restore-archived', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button class="btn btn-sm btn-outline-secondary">Restore Arsip</button>
                                            </form>
                                        @endcan
                                        @can('delete', $post)
                                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan berita ini ke tempat sampah?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                            </form>
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $posts->links() }}
        </div>
    </div>

    @push('scripts')
        <script>
            const realtimeSearch = document.querySelector('[data-realtime-search]');
            const postFilterForm = document.getElementById('postFilterForm');
            let searchTimer;

            if (realtimeSearch && postFilterForm) {
                realtimeSearch.addEventListener('input', () => {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => postFilterForm.requestSubmit(), 450);
                });
            }

            document.querySelector('[data-check-all]')?.addEventListener('change', (event) => {
                document.querySelectorAll('[data-row-check]').forEach((checkbox) => {
                    checkbox.checked = event.target.checked;
                });
            });
        </script>
    @endpush
@endsection
