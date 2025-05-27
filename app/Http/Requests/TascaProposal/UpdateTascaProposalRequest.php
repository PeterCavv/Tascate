<?php

namespace App\Http\Requests\TascaProposal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTascaProposalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,accepted,rejected',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
