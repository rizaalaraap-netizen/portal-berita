<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditorImageUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $mimes = implode(',', config('media.allowed_mimes', ['jpg', 'jpeg', 'png', 'webp', 'svg']));
        $maxUpload = (int) config('media.max_upload_kb', 4096);

        return [
            'upload' => ['required', 'file', "mimes:{$mimes}", "max:{$maxUpload}"],
        ];
    }
}
