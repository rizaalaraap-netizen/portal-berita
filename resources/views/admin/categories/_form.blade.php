@csrf
<div class="mb-3">
    <label class="form-label" for="name">Nama Kategori</label>
    <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label" for="slug">Slug otomatis</label>
    <input id="slug" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}" placeholder="Kosongkan untuk otomatis">
    @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label" for="description">Deskripsi</label>
    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="form-check form-switch mb-4">
    <input id="is_active" name="is_active" value="1" type="checkbox" class="form-check-input" @checked(old('is_active', $category->is_active))>
    <label class="form-check-label" for="is_active">Aktif</label>
</div>
<button class="btn btn-danger">{{ $button }}</button>
<a class="btn btn-outline-secondary" href="{{ route('admin.categories.index') }}">Batal</a>
