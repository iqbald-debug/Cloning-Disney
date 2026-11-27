<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'brand'  => ['nullable', 'string', 'exists:brands,slug'],
            'type'   => ['nullable', 'in:movie,series'],
        ];
    }
}