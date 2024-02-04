<?php

namespace App\Http\Requests;

use App\Models\Petugas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StorePetugasRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama_lengkap' => [
                'string',
                'required',
            ],
            'email' => [
                'required',
                'unique:petugas',
            ],
            'password' => [
                'required',
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
