<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Http\Resources\Admin\TugasResource;

use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Tugas;
use App\Models\PetugasTugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        try {
            $search = $request->search;
            // $contents = Tugas::with('jenis', 'lokasi', 'petugas')->orderBy('id','desc')->get();
            $contents = Tugas::with('petugas', 'lokasi', 'jenis')
            ->where(function ($query) use ($search) {
                $query->where('judul_tugas', 'LIKE', '%'.$search.'%')
                    ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->lokasi_id);
                $jenis = Jenis::findOrFail($value->jenis_id);

                $data[$key]['uid'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan;
                $data[$key]['lokasi'] = $lokasi->nama_jalan;
                $data[$key]['jenis'] = $jenis->nama_jenis;
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['petugas'] = $value->petugas;
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
                'jenis_id' => 'required',
                'lokasi_id' => 'required',
                'petugas' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                ]);
            }

            $tugas = new Tugas();
            $tugas->judul_tugas = $request->judul_tugas;
            $tugas->keterangan = $request->keterangan;
            $tugas->status = 1;
            $tugas->jenis_id = $request->jenis_id;
            $tugas->lokasi_id = $request->lokasi_id;
            $tugas->save();

            // tangkap data
            $ubah1 = explode(',', $request['petugas']);
            $ubah2 = preg_replace('/[^A-Za-z0-9\-]/', '', $ubah1);
            $tugas->petugas()->attach($ubah2);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil menambahkan data'
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
            $tugas = Tugas::where('id', $id)->with('jenis','lokasi','petugas')->first();

            $lokasi = Lokasi::findOrFail($tugas->lokasi_id);
            $jenis = Jenis::findOrFail($tugas->jenis_id);

            $data['id'] = $tugas->id;
            $data['judul_tugas'] = $tugas->judul_tugas;
            $data['keterangan'] = $tugas->keterangan ?? '-';
            $data['status'] = $tugas->status;
            $data['tanggal'] = showDateTime($tugas->created_at);
            $data['lokasi'] = $lokasi->nama_jalan;
            $data['jenis'] = $jenis->nama_jenis;
            $data['petugas'] = $tugas->petugas;

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
                'jenis_id' => 'required',
                'lokasi_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->all(),
                ]);
            }

            $tugas = Tugas::findOrFail($request->id);
            $tugas->update($request->all());

            // tangkap data
            $ubah1 = explode(',', $request['petugas']);
            $ubah2 = preg_replace('/[^A-Za-z0-9\-]/', '', $ubah1);
            $tugas->petugas()->sync($ubah2);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil mengubah tugas',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas gagal disimpan',
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $tugas = Tugas::findOrFail($id);
            if ($tugas) {
                $tugas->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Tugas berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tugas tidak ditemukan',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tugas tidak ditemukan',
            ]);
        }
    }

    public function tugas_anggota(Request $request)
    {
        try {
            $search = $request->search;
            $contents = PetugasTugas::with('tugas')
            ->where('petugas_id', auth()->user()->id)
            ->WhereHas('tugas', function ($query) use ($search) {
                $query->where('status', 1)
                ->where(function ($query2) use ($search) {
                    $query2->where('judul_tugas', 'LIKE', '%'.$search.'%')
                        ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
                });
            })->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->tugas->lokasi_id);
                $jenis = Jenis::findOrFail($value->tugas->jenis_id);
                $data[$key]['id'] = $value->tugas->id;
                $data[$key]['judul'] = $value->tugas->judul_tugas;
                $data[$key]['keterangan'] = $value->tugas->keterangan;
                $data[$key]['lokasi'] = $lokasi->nama_jalan;
                $data[$key]['jenis'] = $jenis->nama_jenis;
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

    public function riwayat_tugas_anggota(Request $request)
    {
        try {
            $search = $request->search;
            $contents = PetugasTugas::with('tugas')
            ->where('petugas_id', auth()->user()->id)
            ->WhereHas('tugas', function ($query) use ($search){
                $query->where('status', 2)
                ->where(function ($query2) use ($search) {
                    $query2->where('judul_tugas', 'LIKE', '%'.$search.'%')
                        ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
                });
            })->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->tugas->lokasi_id);
                $jenis = Jenis::findOrFail($value->tugas->jenis_id);
                $data[$key]['judul'] = $value->tugas->judul_tugas;
                $data[$key]['keterangan'] = $value->tugas->keterangan;
                $data[$key]['lokasi'] = $lokasi->nama_jalan;
                $data[$key]['jenis'] = $jenis->nama_jenis;
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
