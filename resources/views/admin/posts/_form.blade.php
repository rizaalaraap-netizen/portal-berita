@csrf
<div class="row g-3">
    <div class="col-lg-8">
        <div class="mb-3">
            <label class="form-label" for="title">Judul</label>
            <input id="title" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $post->title) }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="slug">Slug otomatis</label>
            <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $post->slug) }}" placeholder="Kosongkan untuk otomatis">
            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="meta_title">Meta Title</label>
            <input id="meta_title" name="meta_title" maxlength="70" class="form-control @error('meta_title') is-invalid @enderror" value="{{ old('meta_title', $post->meta_title) }}" placeholder="Kosongkan untuk memakai judul berita">
            @error('meta_title')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="meta_description">Meta Description</label>
            <textarea id="meta_description" name="meta_description" maxlength="170" rows="3" class="form-control @error('meta_description') is-invalid @enderror" placeholder="Kosongkan untuk memakai ringkasan isi berita">{{ old('meta_description', $post->meta_description) }}</textarea>
            @error('meta_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="excerpt">Ringkasan</label>
            <textarea id="excerpt" name="excerpt" maxlength="500" rows="3" class="form-control @error('excerpt') is-invalid @enderror" placeholder="Ringkasan singkat berita">{{ old('excerpt', $post->excerpt) }}</textarea>
            @error('excerpt')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                <label class="form-label mb-0" for="content">Isi Berita</label>
                <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary"
                    data-editor-media-button
                    data-media-library-url="{{ route('admin.media.library') }}">
                    Media Library
                </button>
            </div>
            <textarea
                id="content"
                name="content"
                class="form-control ckeditor-content @error('content') is-invalid @enderror"
                data-upload-url="{{ route('admin.posts.ckeditor-upload') }}"
                required>{{ old('content', $post->content) }}</textarea>
            @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="border rounded-3 bg-white mt-2 p-2 d-none" data-editor-media-panel>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <input type="search" class="form-control form-control-sm" placeholder="Search gambar..." data-editor-media-search>
                    <button type="button" class="btn btn-sm btn-outline-danger" data-editor-media-load>Pilih</button>
                </div>
                <div class="row g-2" data-editor-media-results></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-3">
            <label class="form-label" for="category_id">Kategori</label>
            <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                <option value="">Pilih kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="author_id">Penulis</label>
            @if($canChooseAuthor)
                <select id="author_id" name="author_id" class="form-select @error('author_id') is-invalid @enderror" required>
                    @foreach($authors as $author)
                        <option value="{{ $author->id }}" @selected(old('author_id', $post->author_id ?: auth()->id()) == $author->id)>{{ $author->name }}</option>
                    @endforeach
                </select>
            @else
                <input type="hidden" name="author_id" value="{{ auth()->id() }}">
                <input class="form-control" value="{{ auth()->user()->name }}" disabled>
            @endif
            @error('author_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="thumbnail">Thumbnail</label>
            <input id="thumbnail" name="thumbnail" type="file" accept="image/*" class="form-control @error('thumbnail') is-invalid @enderror" data-preview-input="#thumbnailPreview">
            @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-3">
                <img id="thumbnailPreview" class="thumbnail-preview" src="{{ $post->exists ? $post->thumbnail_url : asset('images/headline.svg') }}" alt="Preview thumbnail">
                <p class="text-muted small mb-0 mt-2">Preview thumbnail. Jika belum memilih gambar, placeholder akan digunakan.</p>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="thumbnail_media_path">Pilih Thumbnail dari Media</label>
            <select id="thumbnail_media_path" name="thumbnail_media_path" class="form-select" data-media-preview="#thumbnailPreview">
                <option value="">Tidak memilih media</option>
                @foreach($mediaItems ?? collect() as $media)
                    <option value="{{ $media['path'] }}" data-url="{{ $media['url'] }}" @selected(old('thumbnail_media_path', str_starts_with((string) $post->thumbnail, 'media-library/') ? $post->thumbnail : null) === $media['path'])>
                        {{ $media['name'] }}
                    </option>
                @endforeach
            </select>
            <a class="small d-inline-block mt-2" href="{{ route('admin.media.index') }}" target="_blank">Buka Media Manager</a>
        </div>
        <div class="mb-3">
            <label class="form-label" for="og_image">Open Graph Image</label>
            <input id="og_image" name="og_image" type="file" accept="image/*" class="form-control @error('og_image') is-invalid @enderror" data-preview-input="#ogImagePreview">
            @error('og_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <div class="mt-3">
                <img id="ogImagePreview" class="thumbnail-preview" src="{{ $post->exists ? $post->og_image_url : asset('images/headline.svg') }}" alt="Preview Open Graph image">
                <p class="text-muted small mb-0 mt-2">Dipakai untuk Facebook, WhatsApp, X/Twitter, dan preview link.</p>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label" for="og_image_media_path">Pilih Open Graph Image dari Media</label>
            <select id="og_image_media_path" name="og_image_media_path" class="form-select" data-media-preview="#ogImagePreview">
                <option value="">Tidak memilih media</option>
                @foreach($mediaItems ?? collect() as $media)
                    <option value="{{ $media['path'] }}" data-url="{{ $media['url'] }}" @selected(old('og_image_media_path', str_starts_with((string) $post->og_image, 'media-library/') ? $post->og_image : null) === $media['path'])>
                        {{ $media['name'] }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                @foreach($statusOptions as $status => $label)
                    <option value="{{ $status }}" @selected(old('status', $post->status) === $status)>{{ $label }}</option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="published_at">Tanggal Publish</label>
            <input id="published_at" name="published_at" type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
            @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-danger w-100">{{ $button }}</button>
        @if($post->exists)
            <a class="btn btn-outline-secondary w-100 mt-2" href="{{ route('admin.posts.preview', $post) }}" target="_blank">Preview Berita</a>
        @endif
    </div>
</div>

@once
    @push('styles')
        <style>
            .ck-editor__editable_inline {
                min-height: 360px;
            }
        </style>
    @endpush
@endonce
