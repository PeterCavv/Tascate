<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,',
            'password' => 'sometimes|required|string|min:8|confirmed',
            'avatar' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
