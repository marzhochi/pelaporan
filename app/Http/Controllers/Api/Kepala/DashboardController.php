<?php

namespace App\Http\Controllers\Api\Kepala;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Requests\StoreTugasRequest;

use App\Http\Resources\Admin\PenggunaResource;
use App\Http\Resources\Admin\JenisResource;
use App\Http\Resources\Admin\LokasiResource;
use App\Http\Resources\Admin\TugasResource;
use App\Http\Resources\Admin\PengaduanResource;

use App\Models\Pengguna;
use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Tugas;
use App\Models\Pengaduan;

use Symfony\Component\HttpFoundation\Response;

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
        $data = new JenisResource(Jenis::all());
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

    public function pengaduan()
    {
        $pengaduan = Pengaduan::where('status',0)->orderBy('created_at', 'desc')->get();
        $data =  new PengaduanResource($pengaduan);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function penugasan(StoreTugasRequest $request)
    {
        $tugas = Tugas::create($request->all());

        return (new TugasResource($tugas))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
