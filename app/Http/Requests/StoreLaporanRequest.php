<?php

namespace App\Http\Requests;

use App\Models\Laporan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreLaporanRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('laporan_create');
    }

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
