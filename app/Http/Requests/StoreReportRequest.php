<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    // Menentukan apakah user diizinkan melakukan request ini atau tidak.
    public function authorize(): bool
    {
        return true;
    }

    // Aturan validasi untuk form laporan.
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'district_id' => 'required|exists:districts,id',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}