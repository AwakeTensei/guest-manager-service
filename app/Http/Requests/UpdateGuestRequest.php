<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('id');
        return [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => "nullable|email|unique:guests,email,{$id}",
            'phone' => "sometimes|required|string|unique:guests,phone,{$id}|regex:/^\+[1-9]\d{6,14}$/",
            'country' => 'nullable|string|max:255',
        ];
    }
}