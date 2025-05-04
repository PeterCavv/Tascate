<?php

namespace App\Http\Requests\Comment;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->comment->user_id;
    }

    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:255'],
        ];
    }
} 