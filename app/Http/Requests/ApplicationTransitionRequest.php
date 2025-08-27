<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationTransitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $transition = $this->route('transition');

        return match ($transition) {
            'prequalify' => [
                'message' => 'nullable|string|max:500',
            ],
            'interview' => [
                'message' => 'nullable|string|max:500',
                'interview_date' => 'nullable|date|after:today',
            ],
            'approve' => [
                'message' => 'nullable|string|max:500',
            ],
            'reject' => [
                'reason' => 'required|string|max:500',
            ],
            default => [],
        };
    }
}
