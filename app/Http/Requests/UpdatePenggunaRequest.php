<?php

namespace App\Http\Requests;

use App\Models\Pengguna;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePenggunaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:pengguna,email,' . request()->route('pengguna')->id,
            ],
            'nip' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'golongan' => [
                'string',
                'nullable',
            ],
            'no_telp' => [
                'string',
                'min:10',
                'max:13',
                'nullable',
            ],
            'role' => [
                'required',
                'integer',
            ],
        ];
    }
}
