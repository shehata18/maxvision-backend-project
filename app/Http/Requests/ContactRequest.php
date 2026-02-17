<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Public API
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(
            collect($this->all())
                ->map(fn ($value) => is_string($value) ? trim($value) : $value)
                ->toArray()
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:100',
            'project_type' => [
                'required',
                'string',
                'in:Outdoor Advertising,Indoor LED Wall,Transparent Display,LED Poster / Signage,Architectural Facade,Retail Storefront,Corporate / Control Room,Events & Stages,Other',
            ],
            'timeline' => [
                'required',
                'string',
                'in:Immediate (< 1 month),1 – 3 months,3 – 6 months,6 – 12 months,Planning / Research phase',
            ],
            'size_requirements' => 'required|string|min:1|max:500',
            'budget_range' => [
                'required',
                'string',
                'in:Under $10,000,$10,000 – $25,000,$25,000 – $50,000,$50,000 – $100,000,$100,000 – $250,000,$250,000+,Not sure yet',
            ],
            'message' => 'nullable|string|max:2000',
            'honeypot' => 'nullable|string|max:0',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'project_type.required' => 'Please select a project type.',
            'project_type.in' => 'Please select a valid project type.',
            'timeline.required' => 'Please select a timeline.',
            'timeline.in' => 'Please select a valid timeline.',
            'size_requirements.required' => 'Size requirements are needed.',
            'budget_range.required' => 'Please select a budget range.',
            'budget_range.in' => 'Please select a valid budget range.',
            'honeypot.max' => 'Spam detected.',
        ];
    }
}
