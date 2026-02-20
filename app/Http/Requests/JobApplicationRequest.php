<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
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
            'job_id' => ['nullable', 'string', 'exists:job_listings,slug'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'resume' => ['required_without:linkedin_url', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // 10MB max
            'linkedin_url' => ['nullable', 'url', 'max:500'],
            'portfolio_url' => ['nullable', 'url', 'max:500'],
            'is_general' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'resume.required_without' => 'Please upload a resume or provide your LinkedIn URL.',
            'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'Resume file size must not exceed 10MB.',
        ];
    }
}
