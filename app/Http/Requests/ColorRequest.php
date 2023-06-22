<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ColorRequest extends FormRequest
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
            'code' => 'required|string|max:50',
            'name' => 'required|string|max:255',
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
            'code.required' => 'O campo código é obrigatório.',
            'code.string' => 'O código deve ser uma string.',
            'code.max' => 'O tamanho maximo do código é 50.',
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O tamanho maximo do nome é 255.',
        ];
    }
}
