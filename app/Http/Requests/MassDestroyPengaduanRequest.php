<?php

namespace App\Http\Requests;

use App\Models\Pengaduan;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPengaduanRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('pengaduan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:pengaduan,id',
        ];
    }
}
