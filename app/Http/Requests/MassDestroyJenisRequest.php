<?php

namespace App\Http\Requests;

use App\Models\Jenis;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyJenisRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:jenis,id',
        ];
    }
}
