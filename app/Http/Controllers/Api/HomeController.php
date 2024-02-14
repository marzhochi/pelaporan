<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Pengaduan;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        $search = $request->search;

        $contents =  Pengaduan::where('status', 1)
        ->where('judul_pengaduan', 'LIKE', '%'.$search.'%')
        ->get();

        foreach ($contents as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['judul'] = $value->judul_pengaduan;
            $data[$key]['keterangan'] = $value->keterangan;
            $data[$key]['nama_pelapor'] = $value->nama_lengkap;
            $data[$key]['no_telp'] = $value->no_telepon;
            $data[$key]['status'] = $value->status;
            $data[$key]['kelurahan'] = $value->kelurahan;
            $data[$key]['kecamatan'] = $value->kecamatan;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['tanggal'] = $value->created_at;
            $data[$key]['foto'] = isset($value->foto) ? $value->foto->original_url : 'https://izinet.online/assets/images/avatar.png' ;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data Pengaduan',
            'data' => $data,
        ]);
    }

    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
            ]);
        }
    }

    public function show($id)
    {
        try {
            $pengaduan = Pengaduan::where('id', $id)->first();

            $data['id'] = $pengaduan->id;
            $data['avatar'] = isset($pengaduan->media->original_url) ? $pengaduan->media->original_url : '-';

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $pengaduan = Pengaduan::findOrFail($id);
            if ($pengaduan) {
                $pengaduan->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Data berhasil di hapus',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

}
