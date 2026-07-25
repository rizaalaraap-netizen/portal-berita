<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'subjek' => ['required', 'string', 'max:180'],
            'pesan' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }
}
