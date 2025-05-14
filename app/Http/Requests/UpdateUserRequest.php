<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{

    public function prepareForValidation()
    {
        if ($this->has('_method')) {
            $this->setMethod(strtoupper($this->_method));
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email,' . $this->user()->id,
            ],
            'avatar' => ['nullable', 'file', 'image', 'max:2048'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
