<?php

namespace App\Http\Requests;

use App\Models\Tugas;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTugasRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('tugas_edit');
    }

    public function rules()
    {
        return [
            'petugas_id' => [
                'required',
                'integer',
            ],
            'pengaduan_id' => [
                'required',
                'integer',
            ],
            'kategori_id' => [
                'required',
                'integer',
            ],
            'judul_tugas' => [
                'string',
                'required',
            ],
            'keterangan' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
