<?php

namespace App\Http\Requests;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ActivityLogIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('viewAny', ActivityLog::class);
    }

    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'role' => ['nullable', Rule::in(array_keys(User::ROLES))],
            'module' => ['nullable', 'string', 'max:80'],
            'action' => ['nullable', 'string', 'max:80'],
            'search' => ['nullable', 'string', 'max:120'],
        ];
    }
}
