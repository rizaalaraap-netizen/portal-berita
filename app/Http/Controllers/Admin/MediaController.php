<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MediaIndexRequest;
use App\Http\Requests\MediaUpdateRequest;
use App\Http\Requests\MediaUploadRequest;
use App\Models\Media;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function __construct(private readonly MediaService $mediaService)
    {
    }

    public function index(MediaIndexRequest $request): View
    {
        Gate::authorize('viewAny', Media::class);

        $filters = $request->validated();

        return view('admin.media.index', [
            'mediaItems' => $this->mediaService->paginate($filters, $request->user()),
            'filters' => $filters,
            'extensions' => Media::query()->select('extension')->distinct()->orderBy('extension')->pluck('extension'),
            'maxUploadKb' => config('media.max_upload_kb'),
            'allowedMimes' => config('media.allowed_mimes'),
        ]);
    }

    public function library(MediaIndexRequest $request): JsonResponse
    {
        Gate::authorize('viewAny', Media::class);

        $items = $this->mediaService
            ->paginate($request->validated(), $request->user(), 60)
            ->through(fn (Media $media) => [
                'id' => $media->id,
                'url' => $media->url,
                'thumbnail_url' => $media->thumbnail_url,
                'alt' => $media->alt,
                'caption' => $media->caption,
                'name' => $media->original_name,
                'path' => $media->path,
            ]);

        return response()->json($items);
    }

    public function store(MediaUploadRequest $request): RedirectResponse
    {
        Gate::authorize('create', Media::class);

        foreach ($this->uploadedFiles($request) as $file) {
            $this->mediaService->store($file, 'media-library', $request->user());
        }

        return redirect()->route('admin.media.index')->with('success', 'Gambar berhasil diupload.');
    }

    public function update(MediaUpdateRequest $request, Media $media): RedirectResponse
    {
        Gate::authorize('update', $media);

        $media->update($request->validated());

        return back()->with('success', 'Metadata media berhasil diperbarui.');
    }

    public function destroy(Media $media): RedirectResponse
    {
        Gate::authorize('delete', $media);

        $this->mediaService->softDelete($media);

        return redirect()->route('admin.media.index')->with('success', 'Media berhasil dipindahkan ke trash.');
    }

    public function restore(int $media): RedirectResponse
    {
        $media = Media::onlyTrashed()->findOrFail($media);

        Gate::authorize('restore', $media);

        $this->mediaService->restore($media);

        return redirect()->route('admin.media.index', ['status' => 'trash'])->with('success', 'Media berhasil direstore.');
    }

    public function forceDelete(int $media): RedirectResponse
    {
        $media = Media::onlyTrashed()->findOrFail($media);

        Gate::authorize('forceDelete', $media);

        $this->mediaService->forceDelete($media);

        return redirect()->route('admin.media.index', ['status' => 'trash'])->with('success', 'Media berhasil dihapus permanen.');
    }

    public function download(Media $media): StreamedResponse
    {
        Gate::authorize('viewAny', Media::class);

        return Storage::disk($media->disk)->download($media->path, $media->original_name);
    }

    private function uploadedFiles(MediaUploadRequest $request): array
    {
        if ($request->hasFile('images')) {
            return $request->file('images');
        }

        return [$request->file('image')];
    }
}
