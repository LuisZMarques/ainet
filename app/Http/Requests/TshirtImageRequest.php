<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TshirtImageRequest extends FormRequest
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
            'customer_id' => 'nullable|integer|exists:customers,id',
            'category_id' => 'nullable|integer|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'extra_info' => 'nullable|json',
        ];
    }
        /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages() : array
    {
        return [
            'customer_id.integer' => 'O ID do cliente deve ser um número inteiro.',
            'category_id.integer' => 'O ID da categoria deve ser um número inteiro.',
            'name.required' => 'O campo nome da imagem é obrigatório.',
            'name.string' => 'O campo nome da imagem deve ser uma string.',
            'name.max' => 'O tamanho maximo do nome da imagem é 255.',
            'description.string' => 'O campo descrição da imagem deve ser uma string.',
            'extra_info.json' => 'O campo informações extras deve ser um JSON válido.',
        ];
    }
}
