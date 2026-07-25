<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EditorImageUploadRequest;
use App\Services\MediaLibrary;
use Illuminate\Http\JsonResponse;

class EditorImageUploadController extends Controller
{
    public function __invoke(EditorImageUploadRequest $request, MediaLibrary $mediaLibrary): JsonResponse
    {
        $media = $mediaLibrary->store($request->file('upload'), 'editor-images', $request->user());

        return response()->json([
            'url' => $media->url,
            'media_id' => $media->id,
        ]);
    }
}
