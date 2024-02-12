<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Pengaduan;
use App\Models\Penugasan;
use App\Models\Tugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenugasanController extends Controller
{
    use MediaUploadingTrait;

    public function list_pengaduan()
    {
        try {
            $pengaduan = Pengaduan::where('status', 1)->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $pengaduan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function index()
    {
        try {
            $contents = Penugasan::with('petugas', 'pengaduan')->get();

            foreach ($contents as $key => $value) {
                $tugas[$key]['id'] = $value->id;
                $tugas[$key]['judul'] = $value->judul_tugas;
                $tugas[$key]['keterangan'] = $value->keterangan;
                $tugas[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $tugas[$key]['petugas'] = $value->petugas->nama_lengkap;
                $tugas[$key]['pengaduan'] = $value->pengaduan->judul_pengaduan;
                $tugas[$key]['lokasi'] = $value->pengaduan->kelurahan.', '.$value->pengaduan->kecamatan;
            }

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
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
                'judul_tugas' => 'required',
                'keterangan' => 'required',
                'petugas_id' => 'required',
                'pengaduan_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                ]);
            }

            $penugasan = new Penugasan();
            $penugasan->judul_tugas = $request->judul_tugas;
            $penugasan->keterangan = $request->keterangan;
            $penugasan->status = 1;
            $penugasan->petugas_id = $request->petugas_id;
            $penugasan->pengaduan_id = $request->pengaduan_id;
            $penugasan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Penugasan berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penugasan gagal disimpan',
            ]);
        }
    }

    public function show($id)
    {
        try {
            $tugas = Penugasan::where('id', $id)->with('pengaduan','petugas')->first();

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
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
        try {
            $validator = Validator::make($request->all(), [
                'judul_tugas' => 'required',
                'keterangan' => 'required',
                'petugas_id' => 'required',
                'pengaduan_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                ]);
            }

            $penugasan = Penugasan::findOrFail($request->id);
            $penugasan->judul_tugas = $request->judul_tugas;
            $penugasan->keterangan = $request->keterangan;
            $penugasan->petugas_id = $request->petugas_id;
            $penugasan->pengaduan_id = $request->pengaduan_id;
            $penugasan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Penugasan berhasil diubah',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penugasan gagal disimpan',
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $penugasan = Penugasan::findOrFail($id);
            if ($penugasan) {
                $penugasan->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Penugasan berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Penugasan tidak ditemukan',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penugasan tidak ditemukan',
            ]);
        }
    }

    public function penugasan_anggota()
    {
        try {
            $contents = Penugasan::where(['petugas_id' => auth()->user()->id, 'status' => 1])->with('pengaduan')
            ->orderBy('id', 'desc')
            ->get();

            foreach ($contents as $key => $value) {
                $tugas[$key]['id'] = $value->id;
                $tugas[$key]['judul'] = $value->judul_tugas;
                $tugas[$key]['keterangan'] = $value->keterangan;
                $tugas[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $tugas[$key]['petugas'] = $value->petugas->nama_lengkap;
                $tugas[$key]['pengaduan'] = $value->pengaduan->judul_pengaduan;
                $tugas[$key]['lokasi'] = $value->pengaduan->kelurahan.', '.$value->pengaduan->kecamatan;
            }

            // $tugas = $tugas;

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function penugasan_riwayat_anggota()
    {
        try {
            $contents = Penugasan::where(['petugas_id' => auth()->user()->id, 'status' => 2])->with('pengaduan')
            ->orderBy('id', 'desc')
            ->get();

            foreach ($contents as $key => $value) {
                $tugas[$key]['id'] = $value->id;
                $tugas[$key]['judul'] = $value->judul_tugas;
                $tugas[$key]['keterangan'] = $value->keterangan;
                $tugas[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $tugas[$key]['petugas'] = $value->petugas->nama_lengkap;
                $tugas[$key]['pengaduan'] = $value->pengaduan->judul_pengaduan;
                $tugas[$key]['lokasi'] = $value->pengaduan->kelurahan.', '.$value->pengaduan->kecamatan;
            }

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }



    public function data_tugas()
    {
        try {
            $tugas = Tugas::where(['petugas_id' => auth()->user()->id, 'status' => 1])->with('lokasi','jenis','pengaduan')
            ->orderBy('id', 'desc')
            ->get();

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function tugas_detail($id)
    {
        try {
            $tugas = Tugas::where('id', $id)->with('lokasi','jenis','pengaduan','petugas')->first();

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function riwayat_tugas()
    {
        try {
            $tugas = Tugas::where(['petugas_id' => auth()->user()->id, 'status' => 2])->with('lokasi','jenis','pengaduan')
            ->orderBy('id', 'desc')
            ->get();

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }
}
