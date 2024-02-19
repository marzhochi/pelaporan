<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Laporan;
use App\Models\Penugasan;
use App\Models\Tugas;
use App\Models\PenugasanPetugas;
use App\Models\PetugasTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        try {
            $contents = Laporan::with('penugasan', 'tugas', 'petugas')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = isset($value->longitude) ? $value->latitude : '-6.887056';
                $long = isset($value->longitude) ? $value->longitude : '107.6128997';

                $data[$key]['id'] = $value->id;
                if($value->jenis == 1){
                    $data[$key]['judul'] = $value->penugasan->judul_tugas;
                    $data[$key]['keterangan'] = $value->penugasan->keterangan;
                }else{
                    $data[$key]['judul'] = $value->tugas->judul_tugas;
                    $data[$key]['keterangan'] = $value->tugas->keterangan;
                }

                $data[$key]['deskripsi'] = $value->deskripsi;
                $data[$key]['kelurahan'] = $value->kelurahan;
                $data[$key]['kecamatan'] = $value->kecamatan;
                $data[$key]['latitude'] = $value->latitude;
                $data[$key]['longitude'] = $value->longitude;
                $data[$key]['jenis'] = $value->jenis;
                $data[$key]['tanggal'] = showDateTime($value->created_at);
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['foto'] = $value->foto->original_url ?? 'https://dishub.online/images/no_image.png';
                $data[$key]['latlng'] = $lat.",".$long;
            }

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

            if(isset($request->penugasan_id)){
                $penugasan = Penugasan::findOrFail($request->penugasan_id);
                $penugasan->status = 2; //proses
                $penugasan->save();
            }

            if(isset($request->tugas_id)){
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

    public function store_penugasan(Request $request)
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
            $laporan->kelurahan = $request->kelurahan;
            $laporan->kecamatan = $request->kecamatan;
            $laporan->latitude = $request->latitude;
            $laporan->longitude = $request->longitude;
            $laporan->jenis = 1;
            $laporan->petugas_id = auth()->user()->id;
            $laporan->save();

            if ($request->input('foto', false)) {
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
            }

            $status = PenugasanPetugas::where(['penugasan_id'=> $request->penugasan_id, 'petugas_id' => auth()->user()->id])->first();
            $status->status = 2; //proses
            $status->save();

            $penugasan = Penugasan::findOrFail($request->id);
            $penugasan->status = 2; //proses
            $penugasan->save();

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

    public function store_tugas(Request $request)
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
            $laporan->tugas_id = $request->tugas_id;
            $laporan->kelurahan = $request->kelurahan;
            $laporan->kecamatan = $request->kecamatan;
            $laporan->latitude = $request->latitude;
            $laporan->longitude = $request->longitude;
            $laporan->jenis = 2;
            $laporan->petugas_id = auth()->user()->id;
            $laporan->save();

            $status = PetugasTugas::where(['tugas_id'=> $request->tugas_id, 'petugas_id' => auth()->user()->id])->first();
            $status->status = 2; //proses
            $status->save();

            $tugas = Tugas::findOrFail($request->id);
            $tugas->status = 2; //proses
            $tugas->save();

            if ($request->input('foto', false)) {
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
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
            $laporan = Laporan::where('id', $id)->with('penugasan', 'tugas', 'petugas')->first();

            $lat = $laporan->latitude ?? '-6.887056';
            $long = $laporan->longitude ?? '107.6128997';

            $data['id'] = $laporan->id;
            $data['petugas'] = $laporan->petugas->nama_lengkap;
            $data['deskripsi'] = $laporan->deskripsi;
            $data['jenis'] = $laporan->jenis;
            $data['tanggal'] = showDateTime($laporan->created_at);
            $data['kelurahan'] = $laporan->kelurahan ?? '-';
            $data['kecamatan'] = $laporan->kecamatan ?? '-';
            $data['jarak'] = $laporan->jarak ?? '-';
            $data['latlng'] = $lat.",".$long;
            $data['penugasan'] = isset($laporan->penugasan) ? $laporan->penugasan->judul_tugas : '';
            $data['tugas'] = isset($laporan->tugas) ? $laporan->tugas->judul_tugas : '';
            $data['foto'] = isset($laporan->foto) ? $laporan->foto->original_url : 'https://dishub.online/images/no_image.png';

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

    public function penugasan($id)
    {
        try {
            $contents = Laporan::where('penugasan_id', $id)->with('penugasan', 'petugas')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = $value->latitude ?? '-6.887056';
                $long = $value->longitude ?? '107.6128997';

                $data[$key]['id'] = $value->id;
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['deskripsi'] = $value->deskripsi;
                $data[$key]['jenis'] = $value->jenis;
                $data[$key]['tanggal'] = showDateTime($value->created_at);
                $data[$key]['nama_jalan'] = $value->nama_jalan ?? '-';
                $data[$key]['kelurahan'] = $value->kelurahan ?? '-';
                $data[$key]['kecamatan'] = $value->kecamatan ?? '-';
                $data[$key]['jarak'] = $value->jarak ?? '-';
                $data[$key]['latlng'] = $lat.",".$long;
                $data[$key]['penugasan'] = isset($value->penugasan) ? $value->penugasan->judul_tugas : '';
                $data[$key]['foto'] = isset($value->foto) ? $value->foto->original_url : 'https://dishub.online/images/no_image.png';
            }

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

    public function tugas($id)
    {
        try {
            $contents = Laporan::where('tugas_id', $id)->with('tugas', 'petugas')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = $value->latitude ?? '-6.887056';
                $long = $value->longitude ?? '107.6128997';

                $data[$key]['id'] = $value->id;
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['deskripsi'] = $value->deskripsi;
                $data[$key]['jenis'] = $value->jenis;
                $data[$key]['tanggal'] = showDateTime($value->created_at);
                $data[$key]['nama_jalan'] = $value->nama_jalan ?? '-';
                $data[$key]['kelurahan'] = $value->kelurahan ?? '-';
                $data[$key]['kecamatan'] = $value->kecamatan ?? '-';
                $data[$key]['jarak'] = $value->jarak ?? '-';
                $data[$key]['latlng'] = $lat.",".$long;
                $data[$key]['tugas'] = isset($value->tugas) ? $value->tugas->judul_tugas : '';
                $data[$key]['foto'] = isset($value->foto) ? $value->foto->original_url : 'https://dishub.online/images/no_image.png';
            }

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
