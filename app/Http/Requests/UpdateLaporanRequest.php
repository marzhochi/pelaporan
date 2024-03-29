<?php

namespace App\Http\Requests;

use App\Models\Laporan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLaporanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'deskripsi' => [
                'string',
                'required',
            ],
            'foto' => [
                'array',
                'required',
            ],
            'foto.*' => [
                'required',
            ],
        ];
    }
}
