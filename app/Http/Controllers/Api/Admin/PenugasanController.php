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

    public function list_pengaduan(Request $request)
    {
        try {
            $search = $request->search;
            $contents = Pengaduan::where('status', 1)->where('judul_pengaduan', 'LIKE', '%'.$search.'%')->orderBy('created_at', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['judul'] = $value->judul_pengaduan;
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

    public function index(Request $request)
    {
        try {
            $search = $request->search;

            $contents = Penugasan::with('petugas', 'pengaduan')
            ->where(function ($query) use ($search) {
                $query->where('judul_tugas', 'LIKE', '%'.$search.'%')
                    ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan ?? '-';
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['pengaduan'] = $value->pengaduan->judul_pengaduan ?? '-';
                $data[$key]['lokasi'] = $value->pengaduan->kelurahan.', '.$value->pengaduan->kecamatan;
                $data[$key]['tanggal'] = showDateTime($value->created_at);
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

            $pengaduan = Pengaduan::findOrFail($request->pengaduan_id);
            $pengaduan->status = 2; //proses
            $pengaduan->save();

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

            $data['id'] = $tugas->id;
            $data['judul_tugas'] = $tugas->judul_tugas;
            $data['keterangan'] = $tugas->keterangan ?? '-';
            $data['status'] = $tugas->status;
            $data['tanggal'] = showDateTime($tugas->created_at);
            $data['petugas'] = $tugas->petugas->nama_lengkap;
            $data['pengaduan'] = $tugas->pengaduan->judul_pengaduan;
            $data['kelurahan'] = $tugas->pengaduan->kelurahan;
            $data['latitude'] = $tugas->pengaduan->latitude;
            $data['longitude'] = $tugas->pengaduan->longitude;

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

    public function penugasan_anggota(Request $request)
    {
        try {
            $search = $request->search;

            $contents = Penugasan::with('pengaduan')
            ->where(['petugas_id' => auth()->user()->id, 'status' => 1])
            ->where(function ($query) use ($search) {
                $query->where('judul_tugas', 'LIKE', '%'.$search.'%')
                    ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan ?? '-';
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['pengaduan'] = $value->pengaduan->judul_pengaduan ?? '-';
                $data[$key]['lokasi'] = $value->pengaduan->kelurahan.', '.$value->pengaduan->kecamatan;
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

    public function penugasan_riwayat_anggota(Request $request)
    {
        try {
            $search = $request->search;

            $contents = Penugasan::with('pengaduan')
            ->where(['petugas_id' => auth()->user()->id, 'status' => 2])
            ->where(function ($query) use ($search) {
                $query->where('judul_tugas', 'LIKE', '%'.$search.'%')
                    ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $data[$key]['id'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan ?? '-';
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['pengaduan'] = $value->pengaduan->judul_pengaduan ?? '-';
                $data[$key]['lokasi'] = $value->pengaduan->kelurahan.', '.$value->pengaduan->kecamatan;
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

}
