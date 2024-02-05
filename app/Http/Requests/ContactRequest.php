<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ContactRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        return [
            "name" => "required|string|min:3|max:70",
            "phone.countryCode" => "required|string|max:4",
            "phone.regionCode" => "required|string|max:4",
            "phone.number" => "required|string|max:20",
            "email" => "required|email",
            "document" => "required|string|max:14",
        ];
    }

    public function messages()
    {
        return [
            "required" => "O campo :attribute é obrigatório.",
            "string" => "O campo :attribute deve ser uma string.",
            "min" => "O campo :attribute deve ter no mínimo :min caracteres.",
            "max" => "O campo :attribute deve ter no máximo :max caracteres.",
            "email" => "O campo :attribute deve ser um e-mail válido."
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}
