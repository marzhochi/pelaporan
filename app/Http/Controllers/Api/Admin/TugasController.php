<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Http\Resources\Admin\TugasResource;
use App\Http\Requests\StoreTugasRequest;

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
            $data = Tugas::with(['jenis', 'lokasi', 'petugas'])->get();

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
            $tugas = PetugasTugas::select('tugas.lokasi.nama_jalan')->with('tugas')
            ->where('petugas_id', auth()->user()->id)
            ->WhereHas('tugas', function ($query) {
                $query->where('tugas.status', 1);
            })
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

    public function riwayat_tugas_anggota()
    {
        try {
            $tugas = PetugasTugas::with('tugas')
            ->where('petugas_id', auth()->user()->id)
            ->WhereHas('tugas', function ($query) {
                $query->where('tugas.status', 2);
            })
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
