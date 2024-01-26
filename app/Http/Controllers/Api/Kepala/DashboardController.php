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

    public function list_jenis()
    {
        $data = new JenisResource(Jenis::all());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_lokasi()
    {
        $data = new LokasiResource(Lokasi::all());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_petugas()
    {
        $user = Pengguna::where('role',2)->orderBy('nama_lengkap', 'desc')->get();
        $data =  new PenggunaResource($user);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_pengaduan()
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
        try {
            $tugas = Tugas::create($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Pengaduan berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengaduan gagal disimpan',
            ]);
        }
    }

    public function data_penugasan()
    {
        $data = Tugas::with(['petugas', 'pengaduan', 'jenis', 'lokasi'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);

    }

    public function data_tugas()
    {
        $tugas = Tugas::where(['petugas_id' => auth()->user()->id])->with('lokasi','jenis','pengaduan')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tugas,
        ]);
    }

    public function tugas_detail($id)
    {
        $tugas = Tugas::where('id', $id)->with('lokasi','jenis','pengaduan')->first();

        return response()->json([
            'status' => 'success',
            'data' => $tugas,
        ]);
    }
}
