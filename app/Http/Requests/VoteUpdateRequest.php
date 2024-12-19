<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VoteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'total_score' => 'required|numeric',
            'vote_detail' => 'required|array',
            'vote_detail.*.employee_id' => 'required|exists:employees,id',
            'vote_detail.*.score' => 'required|numeric|min:0',
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
            'name.required' => 'Judul harus diisi.',
            'name.numeric' => 'Tipe data Judul harus berupa angka.',
            'vote_detail.required' => 'Detail Pemilihan harus diisi.',
            'vote_detail.array' => 'Detail Pemilihan harus berupa array.',
            'vote_detail.*.employee_id.required' => 'Kandidat harus diisi.',
            'vote_detail.*.employee_id.exists' => 'ID kandidat yang dipilih tidak valid.',
            'vote_detail.*.score.required' => 'Persentase harus diisi.',
            'vote_detail.*.score.numeric' => 'Persentase harus berupa angka.',
            'vote_detail.*.score.min' => 'Persentase minimal harus :min karakter.',
        ];
    }
}
