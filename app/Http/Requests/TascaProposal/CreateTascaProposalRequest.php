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

    public function attributes()
    {
        return [
            'tasca_name' => __('validation.attributes.tasca_name'),
            'address' => __('validation.attributes.address'),
            'telephone' => __('validation.attributes.telephone'),
            'cif' => __('validation.attributes.cif'),
            'cif_picture_path' => __('validation.attributes.cif_picture_path'),
            'owner_name' => __('validation.attributes.owner_name'),
            'owner_email' => __('validation.attributes.owner_email'),
            'dni' => __('validation.attributes.dni'),
            'dni_picture_path' => __('validation.attributes.dni_picture_path'),
            'status' => __('validation.attributes.status'),
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
