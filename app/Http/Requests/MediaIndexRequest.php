<?php

namespace App\Http\Requests;

use App\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class MediaIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('viewAny', Media::class);
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:120'],
            'extension' => ['nullable', 'string', 'max:20'],
            'view' => ['nullable', Rule::in(['grid', 'list'])],
            'sort' => ['nullable', Rule::in(['latest', 'oldest', 'name', 'size'])],
            'status' => ['nullable', Rule::in(['active', 'trash'])],
        ];
    }
}
