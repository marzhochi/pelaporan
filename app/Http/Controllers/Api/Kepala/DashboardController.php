<?php

namespace App\Http\Controllers\Api\Kepala;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Resources\Admin\PenggunaResource;
use App\Http\Resources\Admin\KategoriResource;
use App\Http\Resources\Admin\LokasiResource;
use App\Models\Pengguna;
use App\Models\Kategori;
use App\Models\Lokasi;

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

    public function jenis()
    {
        $data = new KategoriResource(Kategori::all());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function lokasi()
    {
        $data = new LokasiResource(Lokasi::all());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function petugas()
    {
        $user = Pengguna::where('role',2)->orderBy('nama_lengkap', 'desc')->get();
        $data =  new PenggunaResource($user);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
