<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class OrderRequest extends FormRequest
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
            'status' => 'required|string|in:pending,paid,closed,canceled',
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'total_price' => 'required|decimal:2',
            'notes' => 'nullable|string',
            'nif' => 'required|digits:9',
            'address' => 'required|string',
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
            'status.required' => 'O campo status é obrigatório.',
            'status.string' => 'O status deve ser uma string.',
            'status.in' => 'O status selecionado é inválido.',
            'customer_id.required' => 'O campo cliente é obrigatório.',
            'customer_id.integer' => 'O cliente deve ser um inteiro.',
            'customer_id.exists' => 'O cliente selecionado é inválido.',
            'date.required' => 'O campo data é obrigatório.',
            'date.date' => 'A data deve ser uma data.',
            'nif.required' => 'O campo NIF é obrigatório.',
            'nif.digits' => 'O NIF deve conter 9 dígitos.',
            'address.required' => 'O campo endereço é obrigatório.',
            'address.string' => 'O endereço deve ser uma string.',
            'default_payment_type.required' => 'O campo tipo de pagamento padrão é obrigatório.',
            'default_payment_type.string' => 'O tipo de pagamento padrão deve ser uma string.',
            'default_payment_type.in' => 'O tipo de pagamento padrão selecionado é inválido.',
            'default_payment_ref.required' => 'O campo referência de pagamento padrão é obrigatório.',
            'default_payment_ref.string' => 'A referência de pagamento padrão deve ser uma string.',
        ];
    }
}
