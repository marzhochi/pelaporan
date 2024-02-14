<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Resources\Admin\PetugasResource;

use App\Models\Petugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        try {
            $user = Petugas::where('id', auth()->user()->id)->with('lokasi')->first();

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
                'message' => 'Ops... Terjadi kelasahan sistem',
            ]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'required|unique:petugas,nip,' . auth()->user()->id,
            'nama_lengkap' => 'required',
            'email' => 'required|unique:petugas,email,' . auth()->user()->id,
            'golongan' => 'required',
            'no_telp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->all(),
            ]);
        }

        $user = Petugas::findOrFail(auth()->user()->id);
        $user->nip = $request->nip;
        $user->nama_lengkap = $request->nama_lengkap;
        $user->email = $request->email;
        if(isset($request->password)){
            $user->password = Hash::make($request->password);
        }
        $user->golongan = $request->golongan;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->no_telp = $request->no_telp;
        $user->save();

        if ($request->input('foto', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diubah',
        ]);
    }
}
