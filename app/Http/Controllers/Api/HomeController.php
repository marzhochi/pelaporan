<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Models\Jenis;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\Pengaduan;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    use MediaUploadingTrait;

    public function index(Request $request)
    {
        $search = $request->search;

        $contents =  Pengaduan::where('judul_pengaduan', 'LIKE', '%'.$search.'%')
        ->orderBy('id','desc')->get();

        $data = array();
        foreach ($contents as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['judul'] = $value->judul_pengaduan;
            $data[$key]['keterangan'] = $value->keterangan;
            $data[$key]['nama_pelapor'] = $value->nama_lengkap;
            $data[$key]['no_telp'] = $value->no_telepon;
            $data[$key]['status'] = $value->status;
            $data[$key]['kelurahan'] = $value->kelurahan;
            $data[$key]['kecamatan'] = $value->kecamatan;
            $data[$key]['latitude'] = $value->latitude;
            $data[$key]['longitude'] = $value->longitude;
            $data[$key]['tanggal'] = showDateTime($value->created_at);
            $data[$key]['foto'] = isset($value->foto) ? $value->foto->original_url : 'https://dishub.online/images/no_image.png';
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function laporan_list(Request $request)
    {
        try {
            $contents = Laporan::with('penugasan', 'tugas', 'petugas')->get();

            $data = array();
            foreach ($contents as $key => $value) {
                $lat = isset($value->longitude) ? $value->latitude : '-6.887056';
                $long = isset($value->longitude) ? $value->longitude : '107.6128997';

                $data[$key]['id'] = $value->id;
                if($value->jenis == 1){
                    $data[$key]['judul'] = $value->penugasan->judul_tugas;
                    $data[$key]['keterangan'] = $value->penugasan->keterangan;
                }else{
                    $data[$key]['judul'] = $value->tugas->judul_tugas;
                    $data[$key]['keterangan'] = $value->tugas->keterangan;
                }

                $data[$key]['deskripsi'] = $value->deskripsi;
                $data[$key]['kelurahan'] = $value->kelurahan;
                $data[$key]['kecamatan'] = $value->kecamatan;
                $data[$key]['latitude'] = $value->latitude;
                $data[$key]['longitude'] = $value->longitude;
                $data[$key]['jenis'] = $value->jenis == 1 ? 'Laporan Penugasan' : 'Laporan Tugas';
                $data[$key]['tanggal'] = showDateTime($value->created_at);
                $data[$key]['petugas'] = $value->petugas->nama_lengkap;
                $data[$key]['foto'] = $value->foto->original_url ?? 'https://dishub.online/images/no_image.png';
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

                $data[$key]['uid'] = $value->id;
                $data[$key]['judul'] = $value->judul_tugas;
                $data[$key]['keterangan'] = $value->keterangan;
                $data[$key]['lokasi'] = $lokasi->nama_jalan;
                $data[$key]['jenis'] = $jenis->nama_jenis;
                $data[$key]['status'] = $value->status == 1 ? 'Baru': 'Selesai';
                $data[$key]['tanggal'] = showDateTime($value->created_at);
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

    public function laporan_show($id)
    {
        try {
            $laporan = Laporan::where('id', $id)->with('penugasan', 'tugas', 'petugas')->first();

            $lat = $laporan->latitude ?? '-6.887056';
            $long = $laporan->longitude ?? '107.6128997';

            $data['petugas'] = $laporan->petugas->nama_lengkap;
            $data['deskripsi'] = $laporan->deskripsi;
            $data['jenis'] = $laporan->jenis == 1 ? 'Laporan Penugasan' : 'Laporan Tugas';
            $data['tanggal'] = showDateTime($laporan->created_at);
            $data['kelurahan'] = $laporan->kelurahan ?? '-';
            $data['kecamatan'] = $laporan->kecamatan ?? '-';
            $data['jarak'] = $laporan->jarak ?? '-';
            $data['latlng'] = $lat.",".$long;
            if($laporan->jenis == 1){
                $data['judul'] = $laporan->penugasan->judul_tugas;
                $data['keterangan'] = $laporan->penugasan->keterangan;
            }else{
                $data['judul'] = $laporan->tugas->judul_tugas;
                $data['keterangan'] = $laporan->tugas->keterangan;
            }
            $data['foto'] = isset($laporan->foto) ? $laporan->foto->original_url : 'https://dishub.online/images/no_image.png';

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

    public function home(Request $request)
    {
        $search = $request->search;

        $contents =  Pengaduan::where('judul_pengaduan', 'LIKE', '%'.$search.'%')
        ->orderBy('id','desc')->limit(5)->get();

        $pengaduan = array();
        foreach ($contents as $key => $value) {
            $pengaduan[$key]['id'] = $value->id;
            $pengaduan[$key]['judul'] = $value->judul_pengaduan;
            $pengaduan[$key]['keterangan'] = $value->keterangan;
            $pengaduan[$key]['nama_pelapor'] = $value->nama_lengkap;
            $pengaduan[$key]['no_telp'] = $value->no_telepon;
            $pengaduan[$key]['status'] = $value->status;
            $pengaduan[$key]['kelurahan'] = $value->kelurahan;
            $pengaduan[$key]['kecamatan'] = $value->kecamatan;
            $pengaduan[$key]['latitude'] = $value->latitude;
            $pengaduan[$key]['longitude'] = $value->longitude;
            $pengaduan[$key]['tanggal'] = showDateTime($value->created_at);
            $pengaduan[$key]['foto'] = isset($value->foto) ? $value->foto->original_url : 'https://dishub.online/images/no_image.png';
        }

        $get_laporan = Laporan::with('penugasan', 'tugas', 'petugas')->withTrashed()->orderBy('id','desc')->limit(5)->get();

        $laporan = array();
        foreach ($get_laporan as $key => $value) {
            $lat = isset($value->longitude) ? $value->latitude : '-6.887056';
            $long = isset($value->longitude) ? $value->longitude : '107.6128997';

            $laporan[$key]['id'] = $value->id;
            if($value->jenis == 1){
                $laporan[$key]['judul'] = $value->penugasan->judul_tugas;
                $laporan[$key]['keterangan'] = $value->penugasan->keterangan;
            }else{
                $laporan[$key]['judul'] = $value->tugas->judul_tugas;
                $laporan[$key]['keterangan'] = $value->tugas->keterangan;
            }

            $laporan[$key]['deskripsi'] = $value->deskripsi;
            $laporan[$key]['kelurahan'] = $value->kelurahan;
            $laporan[$key]['kecamatan'] = $value->kecamatan;
            $laporan[$key]['latitude'] = $value->latitude;
            $laporan[$key]['longitude'] = $value->longitude;
            $laporan[$key]['jenis'] = $value->jenis == 1 ? 'Laporan Penugasan' : 'Laporan Tugas';
            $laporan[$key]['tanggal'] = showDateTime($value->created_at);
            $laporan[$key]['petugas'] = $value->petugas->nama_lengkap;
            $laporan[$key]['foto'] = $value->foto->original_url ?? 'https://dishub.online/images/no_image.png';
            $laporan[$key]['latlng'] = $lat.",".$long;
        }

        return response()->json([
            'status' => 'success',
            'pengaduan' => $pengaduan,
            'laporan' => $laporan,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $pengaduan = new Pengaduan();
            $pengaduan->nama_lengkap = $request->nama_lengkap;
            $pengaduan->no_telepon = $request->no_telepon;
            $pengaduan->judul_pengaduan = $request->judul_pengaduan;
            $pengaduan->keterangan = $request->keterangan;
            $pengaduan->status = 1;
            $pengaduan->kelurahan = $request->kelurahan;
            $pengaduan->kecamatan = $request->kecamatan;
            $pengaduan->latitude = $request->latitude;
            $pengaduan->longitude = $request->longitude;
            $pengaduan->save();

            if ($request->input('foto', false)) {
                $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pengaduan berhasil disimpan',
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
            $pengaduan = Pengaduan::where('id', $id)->first();

            $lat = $pengaduan->latitude ?? '-6.887056';
            $long = $pengaduan->longitude ?? '107.6128997';

            $data['id'] = $pengaduan->id;
            $data['judul'] = $pengaduan->judul_pengaduan;
            $data['keterangan'] = $pengaduan->keterangan;
            $data['nama_pelapor'] = $pengaduan->nama_lengkap;
            $data['telp'] = $pengaduan->no_telepon ?? '-';
            $data['status'] = $pengaduan->status;
            $data['tanggal'] = showDateTime($pengaduan->created_at);
            $data['kelurahan'] = $pengaduan->kelurahan ?? '-';
            $data['kecamatan'] = $pengaduan->kecamatan ?? '-';
            $data['latitude'] = $lat;
            $data['longitude'] = $long;
            $data['latlng'] = $lat.",".$long;
            $data['foto'] = isset($pengaduan->foto) ? $pengaduan->foto->original_url : 'https://dishub.online/images/no_image.png';

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

    public function delete($id)
    {
        try {
            $pengaduan = Pengaduan::findOrFail($id);
            if ($pengaduan) {
                $pengaduan->delete();

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
