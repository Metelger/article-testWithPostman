<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50|min:3',
            'phone' => 'required|string|between:10,11',
            'email' => 'required|email|unique:App\Models\User,email',
            'password' => 'required|max:16|min:8'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'string' => 'O campo :attribute deve ser do tipo string',
            'email' => 'O campo :attribute deve ter um formato válido',
            'between' => 'O campo :attribute deve seguir o seguinte formato: DD + Número. EX: 14996679408 e ter no mínimo :min caracteres e no máximo :max caracteres.',
            'max' => 'O campo :attribute deve possuir no máximo :max caracteres',
            'min' => 'O campo :attribute deve possuir no mínimo :min caracteres',
            'unique' => 'Impossível realizar o cadastro. Já existe um usuário com o :attribute informado.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
