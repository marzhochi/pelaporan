<?php

namespace App\Http\Requests;

use App\Models\Jenis;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreJenisRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama_jenis' => [
                'string',
                'required',
            ],
        ];
    }
}
