<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Laporan;

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
        $laporan->save();

        if ($request->input('foto', false)) {
            $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan berhasil disimpan',
        ]);
    }

    public function show($id)
    {
        try {
            $laporan = Laporan::where('id', $id)->with('penugasan', 'tugas')->first();

            return response()->json([
                'status' => 'success',
                'data' => $laporan,
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
