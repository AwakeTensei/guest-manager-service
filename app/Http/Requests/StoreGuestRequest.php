<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestRequest extends FormRequest
{

// Авторизация не требуется исходя из ТЗ
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:guests,email',
            'phone' => 'required|string|unique:guests,phone|regex:/^\+[1-9]\d{6,14}$/',
            'country' => 'nullable|string|max:255',
        ];
    }
}