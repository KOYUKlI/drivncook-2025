<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('franchisee');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'location' => 'required|string|max:255',
            'coordinates' => 'nullable|string|max:50',
            'payment_method' => 'required|in:cash,card,mobile',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'location.required' => 'La localisation est obligatoire.',
            'location.max' => 'La localisation ne peut pas dépasser 255 caractères.',
            'coordinates.max' => 'Les coordonnées ne peuvent pas dépasser 50 caractères.',
            'payment_method.required' => 'Le mode de paiement est obligatoire.',
            'payment_method.in' => 'Le mode de paiement sélectionné n\'est pas valide.',
            'items.required' => 'Au moins un article est obligatoire.',
            'items.array' => 'Les articles doivent être fournis sous forme de liste.',
            'items.min' => 'Au moins un article est obligatoire.',
            'items.*.product_id.required' => 'L\'ID du produit est obligatoire.',
            'items.*.product_id.integer' => 'L\'ID du produit doit être un nombre entier.',
            'items.*.quantity.required' => 'La quantité est obligatoire.',
            'items.*.quantity.integer' => 'La quantité doit être un nombre entier.',
            'items.*.quantity.min' => 'La quantité doit être d\'au moins 1.',
            'items.*.unit_price.required' => 'Le prix unitaire est obligatoire.',
            'items.*.unit_price.integer' => 'Le prix unitaire doit être un nombre entier.',
            'items.*.unit_price.min' => 'Le prix unitaire ne peut pas être négatif.',
        ];
    }
}
