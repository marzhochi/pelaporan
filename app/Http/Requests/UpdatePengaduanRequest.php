<?php

namespace App\Http\Requests;

use App\Models\Pengaduan;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdatePengaduanRequest extends FormRequest
{
    public function rules()
    {
        return [
            'nama_lengkap' => [
                'string',
                'required',
            ],
            'no_telepon' => [
                'string',
                'required',
            ],
            'judul_pengaduan' => [
                'string',
                'required',
            ],
            'keterangan' => [
                'string',
                'nullable',
            ],
            'foto' => [
                'array',
                'required',
            ],
            'foto.*' => [
                'required',
            ],
            'latlang' => [
                'string',
                'required',
            ],
        ];
    }
}
