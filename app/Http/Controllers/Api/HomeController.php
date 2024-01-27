<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Resources\Admin\PengaduanResource;
use App\Http\Resources\Admin\LokasiResource;
use App\Models\Pengaduan;
use App\Models\Lokasi;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        $search = $request->search;

        return new PengaduanResource(Pengaduan::with(['lokasi'])
        ->where('status', 1)
        ->where('judul_pengaduan', 'LIKE', '%'.$search.'%')
        ->get());
    }

    public function store(Request $request)
    {
        $lokasi = new Lokasi();
        $lokasi->nama_lokasi = $request->nama_lokasi;
        $lokasi->latlang = $request->latlang;
        $lokasi->kelurahan = $request->kelurahan;
        $lokasi->kecamatan = $request->kecamatan;
        $lokasi->kota = $request->kota;
        $lokasi->provinsi = $request->provinsi;
        $lokasi->kodepos = $request->kodepos;
        $lokasi->save();

        $pengaduan = new Pengaduan();
        $pengaduan->nama_lengkap = $request->nama_lengkap;
        $pengaduan->no_telepon = $request->no_telepon;
        $pengaduan->judul_pengaduan = $request->judul_pengaduan;
        $pengaduan->keterangan = $request->keterangan;
        $pengaduan->lokasi_id = $lokasi->id;
        $pengaduan->save();

        if ($request->input('foto', false)) {
            $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pengaduan berhasil disimpan',
        ]);
    }

    public function show(Pengaduan $pengaduan)
    {
        return new PengaduanResource($pengaduan->load(['lokasi']));
    }

}
