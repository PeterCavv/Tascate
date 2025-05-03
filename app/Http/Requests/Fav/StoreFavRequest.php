<?php

namespace App\Http\Requests\Fav;

use Illuminate\Foundation\Http\FormRequest;

class StoreFavRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tascas_id' => 'required|exists:tascas,id',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
