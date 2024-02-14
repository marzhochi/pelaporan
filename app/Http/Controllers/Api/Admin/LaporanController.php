<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\Tugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        try {
            $data = Laporan::with('penugasan', 'tugas')->get();

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
        try {
            $validator = Validator::make($request->all(), [
                'deskripsi' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                ]);
            }

            $laporan = new Laporan();
            $laporan->deskripsi = $request->deskripsi;
            $laporan->jarak = $request->jarak;
            $laporan->penugasan_id = $request->penugasan_id;
            $laporan->tugas_id = $request->tugas_id;
            $laporan->nama_jalan = $request->nama_jalan;
            $laporan->kelurahan = $request->kelurahan;
            $laporan->kecamatan = $request->kecamatan;
            $laporan->latitude = $request->latitude;
            $laporan->longitude = $request->longitude;
            if($request->penugasan_id){
                $laporan->jenis = 1;
            }
            if($request->tugas_id){
                $laporan->jenis = 2;
            }
            $laporan->save();

            if ($request->input('foto', false)) {
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
            }

            if($request->penugasan_id){
                $penugasan = Penugasan::findOrFail($request->penugasan_id);
                $penugasan->status = 2; //proses
                $penugasan->save();
            }

            if($request->tugas_id){
                $tugas = Tugas::findOrFail($request->tugas_id);
                $tugas->status = 2; //proses
                $tugas->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil disimpan',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan gagal disimpan',
            ]);
        }
    }

    public function show($id)
    {
        try {
            $laporan = Laporan::where('id', $id)->with('penugasan', 'tugas')->first();

            $lat = $laporan->latitude ?? '-6.887056';
            $long = $laporan->longitude ?? '107.6128997';

            $data['id'] = $laporan->id;
            $data['deskripsi'] = $laporan->deskripsi;
            $data['jenis'] = $laporan->jenis;
            $data['tanggal'] = $laporan->created_at;
            $data['nama_jalan'] = $laporan->nama_jalan ?? '-';
            $data['kelurahan'] = $laporan->kelurahan ?? '-';
            $data['kecamatan'] = $laporan->kecamatan ?? '-';
            $data['jarak'] = $laporan->jarak ?? '-';
            $data['latlng'] = "LatLng(lat:".$lat.", lng:".$long.")";
            $data['penugasan'] = isset($laporan->penugasan) ? $laporan->penugasan->judul_tugas : '';
            $data['tugas'] = isset($laporan->tugas) ? $laporan->tugas->judul_tugas : '';

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
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $laporan = Laporan::findOrFail($request->id);
        $laporan->deskripsi = $request->deskripsi;
        $laporan->save();

        if ($request->input('foto', false)) {
            $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil disimpan',
        ]);
    }
}
