<?php

namespace App\Http\Requests\FO;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => ['nullable', 'string', 'max:20'],
            'notification_email_optin' => ['boolean'],
            'locale' => ['string', 'in:en,fr'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'phone' => __('ui.fo.account.fields.phone'),
            'notification_email_optin' => __('ui.fo.account.fields.notification_email_optin'),
            'locale' => __('ui.fo.account.fields.locale'),
        ];
    }
}
