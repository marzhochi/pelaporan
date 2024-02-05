<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Models\Petugas;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        try {
            $user = Petugas::where('role', 2)->with('lokasi')->orderBy('id', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'data' => $user,
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
            'nip' => 'required|unique:petugas',
            'nama_lengkap' => 'required',
            'email' => 'required|email|unique:petugas',
            'password' => 'required',
            'lokasi_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $petugas = new Petugas();
        $petugas->nip = $request->nip;
        $petugas->nama_lengkap = $request->nama_lengkap;
        $petugas->email = $request->email;
        $petugas->password = Hash::make($request->password);
        $petugas->golongan = $request->golongan;
        $petugas->jenis_kelamin = $request->jenis_kelamin;
        $petugas->no_telp = $request->no_telp;
        $petugas->role = 2;
        $petugas->lokasi_id = $request->lokasi_id;
        $petugas->save();

        if ($request->input('foto', false)) {
            $petugas->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Petugas berhasil disimpan',
        ]);
    }

    public function show($id)
    {
        try {
            $data = Petugas::where('id', $id)->with('lokasi')->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Detail Petugas',
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
            'nip' => 'required|unique:petugas,nip,' . $request->id,
            'nama_lengkap' => 'required',
            'email' => 'required|unique:petugas,email,' . $request->id,
            'password' => 'required',
            'lokasi_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $petugas = Petugas::findOrFail($request->id);
        $petugas->nip = $request->nip;
        $petugas->nama_lengkap = $request->nama_lengkap;
        $petugas->email = $request->email;
        if(isset($request->password)){
            $petugas->password = Hash::make($request->password);
        }
        $petugas->golongan = $request->golongan;
        $petugas->jenis_kelamin = $request->jenis_kelamin;
        $petugas->no_telp = $request->no_telp;
        $petugas->lokasi_id = $request->lokasi_id;
        $petugas->save();

        if ($request->input('foto', false)) {
            $petugas->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Petugas berhasil diubah',
        ]);
    }

    public function delete($id)
    {
        try {
            $petugas = Petugas::findOrFail($id);
            if ($petugas) {
                $petugas->delete();

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
