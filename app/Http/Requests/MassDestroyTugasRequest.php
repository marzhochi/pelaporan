<?php

namespace App\Http\Requests;

use App\Models\Tugas;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTugasRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('tugas_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tugas,id',
        ];
    }
}
