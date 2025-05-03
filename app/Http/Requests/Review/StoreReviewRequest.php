<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tasca_id' => 'required|exists:tascas,id',
            'customer_id' => 'required|exists:customers,id',
            'body' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
