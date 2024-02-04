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

    public function index()
    {
        try {
            $data = Lokasi::select('id','nama_jalan','kelurahan','kecamatan','latitude','longitude')->get();

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
            $data = Lokasi::where('id', $id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Detail Lokasi',
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
