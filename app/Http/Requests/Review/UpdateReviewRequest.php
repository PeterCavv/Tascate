<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
