<?php

namespace App\Http\Requests;

use App\Models\Tugas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreTugasRequest extends FormRequest
{
    public function rules()
    {
        return [
            'jenis_id' => [
                'required',
                'integer',
            ],
            'lokasi_id' => [
                'required',
                'integer',
            ],
            'petugas.*' => [
                'integer',
            ],
            'petugas' => [
                'array',
            ],
            'judul_tugas' => [
                'string',
                'required',
            ],
            'keterangan' => [
                'string',
                'required',
            ],
        ];
    }
}
