<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Http\Resources\Admin\TugasResource;
use App\Http\Requests\StoreTugasRequest;
use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Tugas;
use App\Models\PetugasTugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        try {
            $contents = Tugas::with('jenis', 'lokasi', 'petugas')->orderBy('id','desc')->get();

            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->lokasi_id);
                $jenis = Jenis::findOrFail($value->jenis_id);

                $tugas[$key]['uid'] = $value->id;
                $tugas[$key]['judul'] = $value->judul_tugas;
                $tugas[$key]['keterangan'] = $value->keterangan;
                $tugas[$key]['lokasi'] = $lokasi->nama_jalan;
                $tugas[$key]['jenis'] = $jenis->nama_jenis;
                $tugas[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                // $petugas = '';
                // foreach ($value->petugas as $key2 => $value2) {
                //     $petugas[$key2] = $value2->nama_lengkap;
                // }
                $tugas[$key]['petugas'] = $value->petugas;
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

        $tugas = new Tugas();
        $tugas->judul_tugas = $request->judul_tugas;
        $tugas->keterangan = $request->input('petugas', []);
        $tugas->status = 1;
        $tugas->jenis_id = $request->jenis_id;
        $tugas->lokasi_id = $request->lokasi_id;
        $tugas->save();

        $tugas->petugas()->sync($request->input('petugas', []));
        // $tugas->petugas()->attach($request->petugas);

        return response()->json([
            'status' => 'success',
            'message' => 'Penugasan berhasil disimpan'
        ]);
    }

    public function show($id)
    {
        try {
            $tugas = Tugas::where('id', $id)->with('jenis','lokasi','petugas')->first();

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
        $tugas->petugas()->sync($request->input('petugas', []));

        return response()->json([
            'status' => 'success',
            'message' => 'Tugas berhasil diubah',
            'data' => new TugasResource($tugas)
        ]);
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

    public function tugas_anggota()
    {
        try {
            $contents = PetugasTugas::with('tugas')
            ->where('petugas_id', auth()->user()->id)
            ->WhereHas('tugas', function ($query) {
                $query->where('tugas.status', 1);
            })
            ->get();

            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->tugas->lokasi_id);
                $jenis = Jenis::findOrFail($value->tugas->jenis_id);
                $contents[$key]['judul'] = $value->tugas->judul_tugas;
                $contents[$key]['keterangan'] = $value->tugas->keterangan;
                $contents[$key]['lokasi'] = $lokasi->nama_jalan;
                $contents[$key]['jenis'] = $jenis->nama_jenis;
            }

            $tugas = $contents;

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

    public function riwayat_tugas_anggota()
    {
        try {
            $contents = PetugasTugas::with('tugas')
            ->where('petugas_id', auth()->user()->id)
            ->WhereHas('tugas', function ($query) {
                $query->where('tugas.status', 2);
            })
            ->get();

            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->tugas->lokasi_id);
                $jenis = Jenis::findOrFail($value->tugas->jenis_id);
                $contents[$key]['judul'] = $value->tugas->judul_tugas;
                $contents[$key]['keterangan'] = $value->tugas->keterangan;
                $contents[$key]['lokasi'] = $lokasi->nama_jalan;
                $contents[$key]['jenis'] = $jenis->nama_jenis;
            }

            $tugas = $contents;

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
