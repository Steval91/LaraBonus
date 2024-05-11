<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        $employeeId = $this->route('employee');

        return [
            'name' => 'required|min:2',
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($employeeId),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Nama minimal harus :min karakter.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
        ];
    }
}
