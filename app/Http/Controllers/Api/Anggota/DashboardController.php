<?php

namespace App\Http\Controllers\Api\Anggota;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Resources\Admin\PenggunaResource;
use App\Models\Pengguna;

class DashboardController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $user = Pengguna::findOrFail(auth()->user()->id);
        $data =  new PenggunaResource($user);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
