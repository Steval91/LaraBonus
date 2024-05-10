<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BonusStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'total_bonus' => 'required|numeric',
            'bonus_detail' => 'required|array',
            'bonus_detail.*.employee_id' => 'required|exists:employees,id',
            'bonus_detail.*.percentage' => 'required|numeric|min:1|max:100',
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
            'total_bonus.required' => 'Total Pembayaran Bonus harus diisi.',
            'total_bonus.numeric' => 'Tipe data harus berupa angka.',
            'bonus_detail.required' => 'Detail bonus harus diisi.',
            'bonus_detail.array' => 'Detail bonus harus berupa array.',
            'bonus_detail.*.employee_id.required' => 'Karyawan harus diisi.',
            'bonus_detail.*.employee_id.exists' => 'ID karyawan yang dipilih tidak valid.',
            'bonus_detail.*.percentage.required' => 'Persentase harus diisi.',
            'bonus_detail.*.percentage.numeric' => 'Persentase harus berupa angka.',
            'bonus_detail.*.percentage.min' => 'Persentase minimal harus :min karakter.',
            'bonus_detail.*.percentage.max' => 'Persentase maksimal :max karakter.',
        ];
    }
}
