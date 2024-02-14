<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Lokasi;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class LokasiController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        try {
            $search = $request->search;

            $contents =  Lokasi::where(function ($query) use ($search) {
                $query->where('nama_jalan', 'LIKE', '%'.$search.'%')
                    ->orWhere('kelurahan', 'LIKE', '%'.$search.'%')
                    ->orWhere('kecamatan', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = isset($value->latitude) ? $value->latitude : '-6.887056';
                $long = isset($value->longitude) ? $value->longitude : '107.6128997';

                $data[$key]['id'] = $value->id;
                $data[$key]['nama_jalan'] = $value->nama_jalan;
                $data[$key]['kelurahan'] = $value->kelurahan ?? '-';
                $data[$key]['kecamatan'] = $value->kecamatan ?? '-';
                $data[$key]['latitude'] = $lat;
                $data[$key]['longitude'] = $long;
                $data[$key]['latlng'] = $lat.",".$long;
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops... Terjadi kelasahan sistem',
            ]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jalan' => 'required|unique:lokasi',
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $lokasi = new Lokasi();
        $lokasi->nama_jalan = $request->nama_jalan;
        $lokasi->kelurahan = $request->kelurahan;
        $lokasi->kecamatan = $request->kecamatan;
        $lokasi->latitude = $request->latitude;
        $lokasi->longitude = $request->longitude;
        $lokasi->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil disimpan',
        ]);
    }

    public function show($id)
    {
        try {
            $lokasi = Lokasi::where('id', $id)->first();

            $lat = $lokasi->latitude ?? '-6.887056';
            $long = $lokasi->longitude ?? '107.6128997';

            $data['id'] = $lokasi->id;
            $data['nama_jalan'] = $lokasi->nama_jalan;
            $data['kelurahan'] = $lokasi->kelurahan ?? '-';
            $data['kecamatan'] = $lokasi->kecamatan ?? '-';
            $data['latitude'] = $lat;
            $data['longitude'] = $long;
            $data['latlng'] = $lat.",".$long;

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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_jalan' => 'required|unique:lokasi,nama_jalan,' . $request->id,
            'kelurahan' => 'required',
            'kecamatan' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $lokasi = Lokasi::findOrFail($request->id);
        $lokasi->nama_jalan = $request->nama_jalan;
        $lokasi->kelurahan = $request->kelurahan;
        $lokasi->kecamatan = $request->kecamatan;
        $lokasi->latitude = $request->latitude;
        $lokasi->longitude = $request->longitude;
        $lokasi->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Lokasi berhasil diubah',
        ]);
    }

    public function delete($id)
    {
        try {
            $lokasi = Lokasi::findOrFail($id);
            if ($lokasi) {
                $lokasi->delete();

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
