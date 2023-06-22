<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderItemRequest extends FormRequest
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
            'order_id' => 'required|integer|exists:orders,id',
            'tshirt_image_id' => 'required|integer|exists:tshirt_images,id',
            'color_code' => 'required|string|exists:colors,code',
            'size' => 'required|in:XS,S,M,L,XL',
            'qty' => 'required|integer',
            'unit_price' => 'required|numeric',
            'sub_total' => 'required|numeric',
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
            'order_id.required' => 'O campo ID do pedido é obrigatório.',
            'order_id.integer' => 'O ID do pedido deve ser um número inteiro.',
            'tshirt_image_id.required' => 'O campo ID da imagem da t-shirt é obrigatório.',
            'tshirt_image_id.integer' => 'O ID da imagem da t-shirt deve ser um número inteiro.',
            'color_code.required' => 'O campo código da cor é obrigatório.',
            'color_code.string' => 'O código da cor deve ser uma string.',
            'size.required' => 'O campo tamanho é obrigatório.',
            'size.string' => 'O tamanho deve ser uma string.',
            'qty.required' => 'O campo quantidade é obrigatório.',
            'qty.integer' => 'A quantidade deve ser um número inteiro.',
            'unit_price.required' => 'O campo preço unitário é obrigatório.',
            'unit_price.numeric' => 'O preço unitário deve ser um valor numérico.',
            'sub_total.required' => 'O campo subtotal é obrigatório.',
            'sub_total.numeric' => 'O subtotal deve ser um valor numérico.',
        ];
    }
}
