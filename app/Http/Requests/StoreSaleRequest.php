<?php

namespace App\Http\Requests;

use App\Models\StockItem;
use Carbon\Carbon;
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
            'sale_date' => 'required|date|before_or_equal:today',
            'lines' => 'required|array|min:1',
            'lines.*.stock_item_id' => 'nullable|exists:stock_items,id',
            'lines.*.item_label' => 'nullable|string|max:255',
            'lines.*.qty' => 'required|numeric|min:0.01|max:9999.99',
            'lines.*.unit_price_cents' => 'required|integer|min:1',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'sale_date.required' => 'La date de vente est obligatoire.',
            'sale_date.date' => 'La date de vente doit être une date valide.',
            'sale_date.before_or_equal' => 'La date de vente ne peut pas être dans le futur.',
            'lines.required' => 'Au moins une ligne de vente est obligatoire.',
            'lines.array' => 'Les lignes de vente doivent être fournies sous forme de liste.',
            'lines.min' => 'Au moins une ligne de vente est obligatoire.',
            'lines.*.stock_item_id.exists' => 'L\'article sélectionné n\'existe pas.',
            'lines.*.item_label.max' => 'Le libellé de l\'article ne peut pas dépasser 255 caractères.',
            'lines.*.qty.required' => 'La quantité est obligatoire.',
            'lines.*.qty.numeric' => 'La quantité doit être un nombre.',
            'lines.*.qty.min' => 'La quantité doit être supérieure à 0.',
            'lines.*.qty.max' => 'La quantité ne peut pas dépasser 9999.99.',
            'lines.*.unit_price_cents.required' => 'Le prix unitaire est obligatoire.',
            'lines.*.unit_price_cents.integer' => 'Le prix unitaire doit être un nombre entier.',
            'lines.*.unit_price_cents.min' => 'Le prix unitaire doit être supérieur à 0.',
        ];
    }
    
    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check that each line has either a stock_item_id or an item_label
            foreach ($this->input('lines', []) as $index => $line) {
                if (empty($line['stock_item_id']) && empty($line['item_label'])) {
                    $validator->errors()->add(
                        "lines.{$index}", 
                        'Chaque ligne doit avoir soit un article du stock, soit un libellé personnalisé.'
                    );
                }
            }
            
            // Verify stock items are active
            $stockItemIds = collect($this->input('lines', []))
                ->pluck('stock_item_id')
                ->filter()
                ->values()
                ->all();
            
            if (!empty($stockItemIds)) {
                $inactiveItems = StockItem::whereIn('id', $stockItemIds)
                    ->where('is_active', false)
                    ->count();
                    
                if ($inactiveItems > 0) {
                    $validator->errors()->add(
                        'lines',
                        'Certains articles sélectionnés ne sont plus actifs.'
                    );
                }
            }
        });
    }
    
    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Format the sale date if needed
        if ($this->has('sale_date')) {
            $this->merge([
                'sale_date' => Carbon::parse($this->input('sale_date'))->format('Y-m-d')
            ]);
        }
    }
}
