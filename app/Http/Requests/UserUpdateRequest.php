<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => 'required|alpha|min:2',
            'email' => 'required|email|unique:users,email,' . $this->user->id,
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
            'name.alpha' => 'Nama hanya boleh berisi alfabet.',
            'name.min' => 'Nama minimal harus :min karakter.',
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah digunakan.',
        ];
    }
}
