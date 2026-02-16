<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseStudyIndexRequest extends FormRequest
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
            'industry' => 'nullable|string|in:retail,outdoor,corporate,events,architecture',
            'featured' => 'nullable|boolean',
        ];
    }
}
