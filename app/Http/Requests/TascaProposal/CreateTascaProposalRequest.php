<?php

namespace App\Http\Requests\TascaProposal;

use Illuminate\Foundation\Http\FormRequest;

class CreateTascaProposalRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tasca_name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'telephone' => 'required|digits:9',
            'cif' => 'required|string|max:9',
            'cif_picture_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'dni' => 'required|string|max:9',
            'dni_picture_path' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'nullable|in:pending,accepted,rejected',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
