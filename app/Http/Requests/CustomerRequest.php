<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class CustomerRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('users', 'name')->ignore($this->user_id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->user_id),
            ],
            'nif' => 'required|digits:9',
            'address' => 'nullable|string',
            'default_payment_type' => 'nullable|in:VISA,MC,PAYPAL',
            'default_payment_ref' => 'nullable|string|max:255',
            'password' =>   'required',
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
            'nif.required' => 'O campo NIF é obrigatório.',
            'nif.string' => 'O NIF deve ser uma string.',
            'nif.digits' => 'O NIF deve conter 9 dígitos.',
            'address.string' => 'O endereço deve ser uma string.',
            'default_payment_type.string' => 'O tipo de pagamento padrão deve ser uma string.',
            'default_payment_type.in' => 'O tipo de pagamento padrão selecionado é inválido.',
            'default_payment_ref.string' => 'A referência de pagamento padrão deve ser uma string.',
            'default_payment_ref.max' => 'A referência de pagamento padrão deve ter no máximo 255 caracteres.',
            'password.required' => 'A password inicial é obrigatória',
        ];
    }
}
