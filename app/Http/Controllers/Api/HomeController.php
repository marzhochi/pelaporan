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

        $data = array();
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
            $data[$key]['foto'] = isset($value->foto) ? $value->foto->original_url : 'https://dishub.online/images/no_image.png';
        }

        return response()->json([
            'status' => 'success',
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

            $lat = $pengaduan->latitude ?? '-6.887056';
            $long = $pengaduan->longitude ?? '107.6128997';

            $data['id'] = $pengaduan->id;
            $data['judul'] = $pengaduan->judul_pengaduan;
            $data['keterangan'] = $pengaduan->keterangan;
            $data['nama_pelapor'] = $pengaduan->nama_lengkap;
            $data['telp'] = $pengaduan->no_telepon ?? '-';
            $data['status'] = $pengaduan->status;
            $data['kelurahan'] = $pengaduan->kelurahan ?? '-';
            $data['kecamatan'] = $pengaduan->kecamatan ?? '-';
            $data['latitude'] = $lat;
            $data['longitude'] = $long;
            $data['latlng'] = "LatLng(lat:".$lat.", lng:".$long.")";
            $data['foto'] = isset($pengaduan->foto) ? $pengaduan->foto->original_url : 'https://dishub.online/images/no_image.png';

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
                    'message' => 'Data berhasil dihapus',
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
