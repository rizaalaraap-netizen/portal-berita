<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $maxUpload = config('media.max_upload_kb');
        $mimes = implode(',', config('media.allowed_mimes'));

        return [
            'images' => ['required_without:image', 'array'],
            'images.*' => ['file', "mimes:{$mimes}", "max:{$maxUpload}"],
            'image' => ['required_without:images', 'file', "mimes:{$mimes}", "max:{$maxUpload}"],
        ];
    }
}
