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
        $contents = Tugas::where('status', 1)->with('jenis', 'lokasi', 'petugas')->get();

            foreach ($contents as $key => $value) {
                $lokasi = Lokasi::findOrFail($value->tugas->lokasi_id);
                $jenis = Jenis::findOrFail($value->tugas->jenis_id);
                $contents[$key]['judul'] = $value->tugas->judul_tugas;
                $contents[$key]['keterangan'] = $value->tugas->keterangan;
                $contents[$key]['lokasi'] = $lokasi->nama_jalan;
                $contents[$key]['jenis'] = $jenis->nama_jenis;
                $contents[$key]['status'] = $value->tugas->status;
                $contents[$key]['uid'] = $value->tugas->id;
                // $petugas = '';
                // foreach ($value->tugas->petugas->nama_lengkap as $key2 => $value2) {
                //     $petugas = $value2->nama_lengkap.', '.$petugas;
                // }
                // $data[$key]['petugas'] = $petugas;
            }

            $tugas = $contents;

            return response()->json([
                'status' => 'success',
                'data' => $tugas,
            ]);

        // try {
        //     $contents = Tugas::where('status', 1)->with(['jenis', 'lokasi', 'petugas'])->get();

        //     foreach ($contents as $key => $value) {
        //         $lokasi = Lokasi::findOrFail($value->tugas->lokasi_id);
        //         $jenis = Jenis::findOrFail($value->tugas->jenis_id);
        //         $tugas[$key]['judul'] = $value->tugas->judul_tugas;
        //         $tugas[$key]['keterangan'] = $value->tugas->keterangan;
        //         $tugas[$key]['lokasi'] = $lokasi->nama_jalan;
        //         $tugas[$key]['jenis'] = $jenis->nama_jenis;
        //         $tugas[$key]['status'] = $value->tugas->status;
        //         $tugas[$key]['id'] = $value->tugas->id;
        //         // $petugas = '';
        //         // foreach ($value->tugas->petugas->nama_lengkap as $key2 => $value2) {
        //         //     $petugas = $value2->nama_lengkap.', '.$petugas;
        //         // }
        //         // $data[$key]['petugas'] = $petugas;
        //     }

        //     return response()->json([
        //         'status' => 'success',
        //         'data' => $tugas,
        //     ]);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Data tidak ditemukan',
        //     ]);
        // }
    }

    public function store(StoreTugasRequest $request)
    {
        $tugas = Tugas::create($request->all());
        $tugas->petugas()->sync($request->input('petugas', []));

        return response()->json([
            'status' => 'success',
            'message' => 'Penugasan berhasil disimpan',
            'data' => new TugasResource($tugas)
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
