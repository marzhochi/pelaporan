<?php

namespace App\Http\Requests;

use App\Models\Tugas;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTugasRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:tugas,id',
        ];
    }
}
