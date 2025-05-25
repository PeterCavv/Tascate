<?php


namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'name' => ['required', 'string', 'max:255'],
//            'surname' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees,email,' . $this->employee->id],
//            'phone' => ['required', 'string', 'max:255'],
//            'address' => ['required', 'string', 'max:255'],
//            'city' => ['required', 'string', 'max:255'],
//            'postal_code' => ['required', 'string', 'max:255'],
//            'country' => ['required', 'string', 'max:255'],
//            'birth_date' => ['required', 'date'],
//            'start_date' => ['required', 'date'],
//            'end_date' => ['nullable', 'date'],
//            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
//            'role' => ['required', 'string', 'in:employee,manager'],
//            'department' => ['required', 'string', 'max:255'],
//            'salary' => ['required', 'numeric', 'min:0'],
//            'status' => ['required', 'string', 'in:active,inactive'],
//            'notes' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {

        return true;
    }
}
