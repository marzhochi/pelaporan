<?php

namespace App\Http\Requests;

use App\Models\Kategori;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateKategoriRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama_kategori' => [
                'string',
                'required',
            ],
        ];
    }
}
