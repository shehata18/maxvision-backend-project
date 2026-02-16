<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public API
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => 'nullable|string|in:outdoor,indoor,transparent,posters',
            'pixel_pitch_min' => 'nullable|numeric|min:0|max:50',
            'pixel_pitch_max' => 'nullable|numeric|min:0|max:50|gte:pixel_pitch_min',
            'brightness_min' => 'nullable|integer|min:0|max:20000',
            'search' => 'nullable|string|max:100',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50',
        ];
    }
}
