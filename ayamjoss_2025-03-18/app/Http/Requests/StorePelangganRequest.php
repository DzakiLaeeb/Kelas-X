<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePelangganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ubah ke true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pelanggan' => 'required|string|max:255',
            'email' => 'required|email|unique:pelanggans,email',
            'password' => 'required|min:6',
            'alamat' => 'required|string',
            'telp' => 'required|string',
            'jeniskelamin' => 'required|in:P,L',
        ];
    }
}

