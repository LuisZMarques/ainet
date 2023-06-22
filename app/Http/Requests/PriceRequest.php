<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PriceRequest extends FormRequest
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
            'unit_price_catalog' => 'required|decimal:2',
            'unit_price_own' => 'required|decimal:2',
            'unit_price_catalog_discount' => 'required|decimal:2',
            'unit_price_own_discount' => 'required|decimal:2',
            'qty_discount' => 'required|integer',
        ];
    }

        /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'unit_price_catalog.required' => 'O campo preço unitário do catálogo é obrigatório.',
            'unit_price_catalog.decimal' => 'O preço unitário do catálogo deve ter duas casas decimais.',
            'unit_price_own.required' => 'O campo preço unitário próprio é obrigatório.',
            'unit_price_own.decimal' => 'O preço unitário próprio deve ter duas casas decimais.',
            'unit_price_catalog_discount.required' => 'O campo preço unitário do catálogo com desconto é obrigatório.',
            'unit_price_catalog_discount.decimal' => 'O preço unitário do catálogo com desconto deve ter duas casas decimais.',
            'unit_price_own_discount.required' => 'O campo preço unitário próprio com desconto é obrigatório.',
            'unit_price_own_discount.decimal' => 'O preço unitário próprio com desconto deve ter duas casas decimais.',
            'qty_discount.required' => 'O campo quantidade de desconto é obrigatório.',
            'qty_discount.integer' => 'A quantidade de desconto deve ser um número inteiro.',
        ];
    }
}
