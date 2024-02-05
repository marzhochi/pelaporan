<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePengaduanRequest;

use App\Http\Resources\Admin\PengaduanResource;
use App\Models\Pengaduan;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        $search = $request->search;

        $pengaduan =  Pengaduan::where('status', 1)
        ->where('judul_pengaduan', 'LIKE', '%'.$search.'%')
        ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pengaduan',
            'data' => $pengaduan,
        ]);
    }

    public function store(Request $request)
    {
        $pengaduan = new Pengaduan();
        $pengaduan->nama_lengkap = $request->nama_lengkap;
        $pengaduan->no_telepon = $request->no_telepon;
        $pengaduan->judul_pengaduan = $request->judul_pengaduan;
        $pengaduan->keterangan = $request->keterangan;
        $pengaduan->status = 1;
        $pengaduan->kelurahan = $request->kelurahan;
        $pengaduan->kecamatan = $request->kecamatan;
        $pengaduan->latitude = $request->latitude;
        $pengaduan->longitude = $request->longitude;
        $pengaduan->save();

        if ($request->input('foto', false)) {
            $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil disimpan',
        ]);
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::where('id', $id)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Detail Pengaduan',
            'data' => $pengaduan,
        ]);
    }

}
