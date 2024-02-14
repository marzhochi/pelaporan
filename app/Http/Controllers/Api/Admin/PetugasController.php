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

    public function index(Request $request)
    {
        try {
            $search = $request->search;

            $contents =  Petugas::where('role', 2)
            ->where(function ($query) use ($search) {
                $query->where('nama_lengkap', 'LIKE', '%'.$search.'%')
                    ->orWhere('nip', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = isset($value->lokasi) ? $value->lokasi->latitude : '-6.887056';
                $long = isset($value->lokasi) ? $value->lokasi->longitude : '107.6128997';

                $data[$key]['id'] = $value->id;
                $data[$key]['nama_lengkap'] = $value->nama_lengkap;
                $data[$key]['nip'] = $value->nip;
                $data[$key]['email'] = $value->email;
                $data[$key]['golongan'] = $value->golongan ?? '-';
                $data[$key]['no_telp'] = $value->no_telp ?? '-';
                $data[$key]['jenis_kelamin'] = $value->jenis_kelamin ?? '-';
                $data[$key]['role'] = $value->role;
                $data[$key]['lokasi_tugas'] = isset($value->lokasi) ? $value->lokasi->nama_jalan : '-';
                $data[$key]['lokasi_kelurahan'] = isset($value->lokasi) ? $value->lokasi->kelurahan : '-';
                $data[$key]['lokasi_kecamatan'] = isset($value->lokasi) ? $value->lokasi->kecamatan : '-';
                $data[$key]['lokasi_latitude'] = $lat;
                $data[$key]['lokasi_longitude'] = $long;
                $data[$key]['lokasi_latlng'] = "LatLng(lat:".$lat.", lng:".$long.")";
                $data[$key]['avatar'] = isset($value->avatar) ? $value->avatar->original_url : 'https://dishub.online/images/no_avatar.jpg';
            }

            return response()->json([
                'status' => 'success',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ops... Terjadi kelasahan sistem',
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
            ]);
        }
    }

    public function show($id)
    {
        try {
            $user = Petugas::where('id', $id)->with('lokasi')->first();

            $lat = isset($user->lokasi) ? $user->lokasi->latitude : '-6.887056';
            $long = isset($user->lokasi) ? $user->lokasi->longitude : '107.6128997';

            $data['id'] = $user->id;
            $data['nama_lengkap'] = $user->nama_lengkap;
            $data['nip'] = $user->nip;
            $data['golongan'] = $user->golongan ?? '-';
            $data['email'] = $user->email;
            $data['jenis_kelamin'] = $user->jenis_kelamin ?? '-';
            $data['no_telp'] = $user->no_telp ?? '-';
            $data['role'] = $user->role;
            $data['lokasi_tugas'] = isset($user->lokasi) ? $user->lokasi->nama_jalan : '-';
            $data['lokasi_kelurahan'] = isset($user->lokasi) ? $user->lokasi->kelurahan : '-';
            $data['lokasi_kecamatan'] = isset($user->lokasi) ? $user->lokasi->kecamatan : '-';
            $data['lokasi_latitude'] = $lat;
            $data['lokasi_longitude'] = $long;
            $data['lokasi_latlng'] = "LatLng(lat:".$lat.", lng:".$long.")";
            $data['avatar'] = isset($user->avatar) ? $user->avatar->original_url : 'https://dishub.online/images/no_avatar.jpg';

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
                    'message' => 'Data berhasil dihapus',
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
