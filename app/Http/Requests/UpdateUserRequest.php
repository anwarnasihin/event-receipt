<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')
                    ->ignore($this->user),
            ],

            'email' => [

                'required',
                'email',
                'max:255',

                Rule::unique('users', 'email')
                    ->ignore($this->user),
            ],

            'location' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],

            'role' => [
                'required',
                'exists:roles,name',
            ],

        ];
    }

    /**
     * Custom Message
     */
    public function messages(): array
    {
        return [

            'name.required' => 'Nama wajib diisi.',

            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'location.required' => 'Lokasi wajib diisi.',
            'location.string' => 'Lokasi tidak valid.',
            'location.max' => 'Lokasi maksimal 255 karakter.',

            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'password.min' => 'Password minimal 8 karakter.',

            'role.required' => 'Role wajib dipilih.',
            'role.exists' => 'Role tidak ditemukan.',

        ];
    }
}
