<?php

namespace App\Http\Requests;

use App\Models\Lokasi;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateLokasiRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('lokasi_edit');
    }

    public function rules()
    {
        return [
            'nama_lokasi' => [
                'string',
                'required',
            ],
            'kelurahan' => [
                'string',
                'nullable',
            ],
            'kecamatan' => [
                'string',
                'nullable',
            ],
            'kota' => [
                'string',
                'nullable',
            ],
            'provinsi' => [
                'string',
                'nullable',
            ],
            'kodepos' => [
                'string',
                'nullable',
            ],
        ];
    }
}
