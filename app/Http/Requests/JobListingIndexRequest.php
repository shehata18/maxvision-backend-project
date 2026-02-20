<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobListingIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', 'in:engineering,sales,marketing,operations,customer_support,finance,human_resources,design'],
            'department' => ['nullable', 'string', 'max:100'],
            'location' => ['nullable', 'string', 'in:toronto,vancouver,montreal,remote,hybrid'],
            'job_type' => ['nullable', 'string', 'in:full-time,part-time,contract,internship,remote'],
            'search' => ['nullable', 'string', 'max:255'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:50'],
        ];
    }
}
