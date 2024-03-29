<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;

use App\Http\Resources\Admin\TugasResource;

use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Petugas;
use App\Models\Tugas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TugasController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        try {
            $search = $request->search;
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

                $data[$key]['id'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan;
                $data[$key]['lokasi'] = $lokasi->nama_jalan;
                $data[$key]['jenis'] = $jenis->nama_jenis;
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['tanggal'] = showDateTime($value->tanggal);
                foreach ($value->petugas as $key2 => $user) {
                    $petugas[$key2] = $user->nama_lengkap;
                }

                $data[$key]['petugas'] = $petugas;
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
                'tanggal' => 'required',
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
            $tugas->tanggal = $request->tanggal;
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

            // $lokasi = Lokasi::findOrFail($tugas->lokasi_id);
            // $jenis = Jenis::findOrFail($tugas->jenis_id);

            $lat = isset($tugas->lokasi->latitude) ? $tugas->lokasi->latitude : '-6.887056';
            $long = isset($tugas->lokasi->longitude) ? $tugas->lokasi->longitude : '107.6128997';

            $data['id'] = $tugas->id;
            $data['judul_tugas'] = $tugas->judul_tugas;
            $data['keterangan'] = $tugas->keterangan ?? '-';
            $data['status'] = $tugas->status;
            $data['tanggal'] = $tugas->tanggal;
            $data['lokasi'] = $tugas->lokasi->nama_jalan;
            $data['latitude'] = $tugas->lokasi->latitude ? $tugas->lokasi->latitude : '-6.887056';
            $data['longitude'] = $tugas->lokasi->longitude ? $tugas->lokasi->longitude : '107.6128997';
            $data['latlng'] = $lat.",".$long;
            $data['jenis'] = $tugas->jenis->nama_jenis;
            foreach ($tugas->petugas as $key => $user) {
                $petugas[$key] = $user->nama_lengkap;
            }

            $data['petugas'] = $petugas;

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
                'tanggal' => 'required',
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

            $contents = Tugas::with('petugas', 'lokasi', 'jenis')
            ->where(function ($query) use ($search) {
                $query->where('judul_tugas', 'LIKE', '%'.$search.'%')
                ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
            })
            ->whereHas('petugas', function ($query){
                $query->where('petugas_id', auth()->user()->id);
                $query->where('status', 1);
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = isset($value->lokasi->latitude) ? $value->lokasi->latitude : '-6.887056';
                $long = isset($value->lokasi->longitude) ? $value->lokasi->longitude : '107.6128997';

                $data[$key]['id'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan ?? '-';
                $data[$key]['lokasi'] = $value->lokasi->kelurahan.', '.$value->lokasi->kecamatan;
                $data[$key]['jenis'] = $value->jenis->nama_jenis ?? '-';
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['latlng'] = $lat.",".$long;
                // foreach ($value->petugas as $key2 => $user) {
                //     $petugas[$key2] = $user->nama_lengkap;
                // }
                // $data[$key]['petugas'] = $petugas;
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

            $contents = Tugas::with('petugas', 'lokasi', 'jenis')
            ->where(function ($query) use ($search) {
                $query->where('judul_tugas', 'LIKE', '%'.$search.'%')
                ->orWhere('keterangan', 'LIKE', '%'.$search.'%');
            })
            ->whereHas('petugas', function ($query){
                $query->where('petugas_id', auth()->user()->id);
                $query->where('status', 2);
            })
            ->orderBy('id', 'desc')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = isset($value->lokasi->latitude) ? $value->lokasi->latitude : '-6.887056';
                $long = isset($value->lokasi->longitude) ? $value->lokasi->longitude : '107.6128997';

                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan ?? '-';
                $data[$key]['lokasi'] = $value->lokasi->kelurahan.', '.$value->lokasi->kecamatan;
                $data[$key]['jenis'] = $value->jenis->nama_jenis ?? '-';
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['latlng'] = $lat.",".$long;
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
