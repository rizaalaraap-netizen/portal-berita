@extends('layouts.admin')

@section('title', 'Media Manager | Portal Berita Admin')
@section('page_title', 'Media Manager')

@section('content')
    <div class="row g-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Upload Gambar</h5>
                    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" data-media-upload-form>
                        @csrf
                        <label class="border rounded-3 bg-light d-block text-center p-4 mb-3" data-drop-zone>
                            <input
                                id="mediaUpload"
                                name="images[]"
                                type="file"
                                accept="image/jpeg,image/png,image/webp,image/svg+xml"
                                class="d-none @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                multiple
                                required>
                            <strong>Drag & drop gambar</strong>
                            <span class="d-block text-muted small mt-1">atau klik untuk memilih beberapa file.</span>
                        </label>
                        @error('images')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        @error('images.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

                        <div class="row g-2 mb-3" data-media-upload-preview></div>

                        <div class="progress d-none mb-3" style="height: 8px;" data-media-progress-wrap>
                            <div class="progress-bar bg-danger" style="width: 0%;" data-media-progress></div>
                        </div>

                        <p class="text-muted small mb-3">
                            Format: {{ implode(', ', $allowedMimes) }}. Maksimal {{ number_format($maxUploadKb / 1024, 1) }} MB per file.
                        </p>
                        <button class="btn btn-danger w-100">Upload</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form class="row g-2 align-items-end mb-4" method="GET">
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Search</label>
                            <input class="form-control" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, alt, caption...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Extension</label>
                            <select class="form-select" name="extension">
                                <option value="">Semua</option>
                                @foreach($extensions as $extension)
                                    <option value="{{ $extension }}" @selected(($filters['extension'] ?? '') === $extension)>{{ strtoupper($extension) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" @selected(($filters['status'] ?? 'active') === 'active')>Aktif</option>
                                <option value="trash" @selected(($filters['status'] ?? '') === 'trash')>Trash</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">Sort</label>
                            <select class="form-select" name="sort">
                                <option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>Terbaru</option>
                                <option value="oldest" @selected(($filters['sort'] ?? '') === 'oldest')>Terlama</option>
                                <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Nama</option>
                                <option value="size" @selected(($filters['sort'] ?? '') === 'size')>Ukuran</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small text-muted">View</label>
                            <select class="form-select" name="view">
                                <option value="grid" @selected(($filters['view'] ?? 'grid') === 'grid')>Grid</option>
                                <option value="list" @selected(($filters['view'] ?? '') === 'list')>List</option>
                            </select>
                        </div>
                        <div class="col-12 d-flex gap-2">
                            <button class="btn btn-outline-danger">Terapkan</button>
                            <a class="btn btn-outline-secondary" href="{{ route('admin.media.index') }}">Reset</a>
                        </div>
                    </form>

                    @if(($filters['view'] ?? 'grid') === 'list')
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Nama</th>
                                        <th>Dimensi</th>
                                        <th>Ukuran</th>
                                        <th>Pemilik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mediaItems as $media)
                                        <tr>
                                            <td><img src="{{ $media->thumbnail_url }}" alt="{{ $media->alt ?: $media->original_name }}" class="rounded-2" style="width:72px;height:56px;object-fit:cover;"></td>
                                            <td>
                                                <strong>{{ $media->original_name }}</strong>
                                                <div class="small text-muted">{{ $media->path }}</div>
                                            </td>
                                            <td class="small text-muted">{{ $media->width && $media->height ? $media->width.' x '.$media->height : '-' }}</td>
                                            <td class="small text-muted">{{ $media->human_size }}</td>
                                            <td class="small text-muted">{{ $media->user?->name ?? '-' }}</td>
                                            <td>@include('admin.media.partials.actions', ['media' => $media, 'filters' => $filters])</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-muted">Belum ada media.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="row g-3">
                            @forelse($mediaItems as $media)
                                <div class="col-md-6 col-xxl-4">
                                    <div class="border rounded-3 p-2 h-100 bg-white">
                                        <a href="{{ $media->url }}" target="_blank">
                                            <img src="{{ $media->thumbnail_url }}" alt="{{ $media->alt ?: $media->original_name }}" class="w-100 rounded-3 mb-2" style="height: 160px; object-fit: cover;">
                                        </a>
                                        <p class="small fw-semibold mb-1 text-truncate" title="{{ $media->original_name }}">{{ $media->original_name }}</p>
                                        <p class="small text-muted mb-2">
                                            {{ strtoupper($media->extension) }} | {{ $media->human_size }}
                                            @if($media->width && $media->height)
                                                | {{ $media->width }} x {{ $media->height }}
                                            @endif
                                        </p>
                                        <div class="input-group input-group-sm mb-2">
                                            <input class="form-control" value="{{ $media->url }}" readonly>
                                            <button class="btn btn-outline-secondary" type="button" data-copy-url="{{ $media->url }}">Copy</button>
                                        </div>
                                        <form action="{{ route('admin.media.update', $media) }}" method="POST" class="border-top pt-2 mt-2">
                                            @csrf
                                            @method('PUT')
                                            <input class="form-control form-control-sm mb-2" name="original_name" value="{{ old('original_name', $media->original_name) }}" placeholder="Nama file">
                                            <input class="form-control form-control-sm mb-2" name="alt" value="{{ old('alt', $media->alt) }}" placeholder="Alt text">
                                            <textarea class="form-control form-control-sm mb-2" name="caption" rows="2" placeholder="Caption">{{ old('caption', $media->caption) }}</textarea>
                                            <button class="btn btn-sm btn-outline-primary w-100">Simpan Metadata</button>
                                        </form>
                                        @include('admin.media.partials.actions', ['media' => $media, 'filters' => $filters])
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <p class="text-muted mb-0">Belum ada media.</p>
                                </div>
                            @endforelse
                        </div>
                    @endif

                    <div class="mt-4">
                        {{ $mediaItems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-copy-url]').forEach((button) => {
            button.addEventListener('click', async () => {
                await navigator.clipboard.writeText(button.dataset.copyUrl);
                button.textContent = 'Copied';
                setTimeout(() => button.textContent = 'Copy', 1200);
            });
        });

        const mediaInput = document.querySelector('#mediaUpload');
        const dropZone = document.querySelector('[data-drop-zone]');
        const mediaPreview = document.querySelector('[data-media-upload-preview]');
        const mediaForm = document.querySelector('[data-media-upload-form]');
        const progressWrap = document.querySelector('[data-media-progress-wrap]');
        const progress = document.querySelector('[data-media-progress]');

        const renderUploadPreview = () => {
            mediaPreview.innerHTML = '';

            Array.from(mediaInput.files || []).slice(0, 8).forEach((file) => {
                const item = document.createElement('div');
                item.className = 'col-4';
                item.innerHTML = `<img class="w-100 rounded-2" style="height:72px;object-fit:cover;" alt="${file.name}"><span class="small text-muted text-truncate d-block">${file.name}</span>`;
                item.querySelector('img').src = URL.createObjectURL(file);
                mediaPreview.appendChild(item);
            });
        };

        mediaInput?.addEventListener('change', renderUploadPreview);

        dropZone?.addEventListener('dragover', (event) => {
            event.preventDefault();
            dropZone.classList.add('border-danger');
        });

        dropZone?.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-danger');
        });

        dropZone?.addEventListener('drop', (event) => {
            event.preventDefault();
            dropZone.classList.remove('border-danger');
            if (mediaInput) {
                mediaInput.files = event.dataTransfer.files;
                renderUploadPreview();
            }
        });

        mediaForm?.addEventListener('submit', () => {
            progressWrap?.classList.remove('d-none');
            if (progress) progress.style.width = '100%';
        });
    </script>
@endpush
