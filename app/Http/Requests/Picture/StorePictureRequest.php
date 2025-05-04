<?php

namespace App\Http\Requests\Picture;

use Illuminate\Foundation\Http\FormRequest;

class StorePictureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->post->user_id;
    }

    public function rules(): array
    {
        return [
            'picture' => ['required', 'image', 'max:2048'], // 2MB max
        ];
    }
} 