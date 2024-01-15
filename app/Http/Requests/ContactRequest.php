<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function authorize()
    {

        return true;
    }

    public function rules()
    {
        return [
            'name' => '',
            'phone.countryCode' => '',
            'phone.regionCode' => '',
            'phone.number' => '',
            'email' => 'email',
            'document' => '',
        ];
    }
}