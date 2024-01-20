<?php

namespace App\Http\Requests;

use App\Models\Pengguna;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyPenggunaRequest extends FormRequest
{
    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:pengguna,id',
        ];
    }
}
