<div class="d-flex flex-wrap gap-2 mt-2">
    <a class="btn btn-sm btn-outline-primary" href="{{ $media->url }}" target="_blank">Preview</a>
    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.media.download', $media) }}">Download</a>

    @if(($filters['status'] ?? 'active') === 'trash')
        <form action="{{ route('admin.media.restore', $media->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <button class="btn btn-sm btn-outline-success">Restore</button>
        </form>
        <form action="{{ route('admin.media.force-delete', $media->id) }}" method="POST" onsubmit="return confirm('Hapus permanen media ini?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
        </form>
    @else
        <form action="{{ route('admin.media.destroy', $media) }}" method="POST" onsubmit="return confirm('Pindahkan media ini ke trash?')">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">Hapus</button>
        </form>
    @endif
</div>
