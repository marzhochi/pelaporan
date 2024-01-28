<?php

namespace App\Http\Controllers\Api\Kepala;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\StoreLaporanRequest;

use App\Http\Resources\Admin\PenggunaResource;
use App\Http\Resources\Admin\JenisResource;
use App\Http\Resources\Admin\LokasiResource;
use App\Http\Resources\Admin\TugasResource;
use App\Http\Resources\Admin\PengaduanResource;

use App\Models\Pengguna;
use App\Models\Jenis;
use App\Models\Lokasi;
use App\Models\Tugas;
use App\Models\Pengaduan;
use App\Models\Laporan;

use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $user = Pengguna::findOrFail(auth()->user()->id);
        $data =  new PenggunaResource($user);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_jenis()
    {
        $data = new JenisResource(Jenis::all());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_lokasi()
    {
        $data = new LokasiResource(Lokasi::all());
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_petugas()
    {
        $user = Pengguna::where('role',2)->orderBy('nama_lengkap', 'desc')->get();
        $data =  new PenggunaResource($user);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function list_pengaduan()
    {
        $pengaduan = Pengaduan::where('status',0)->orderBy('created_at', 'desc')->get();
        $data =  new PengaduanResource($pengaduan);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function penugasan(StoreTugasRequest $request)
    {
        try {
            if($request->pengaduan_id){
                $lokasi_id = Pengaduan::findOrFail($request->pengaduan_id)->lokasi_id;
            }else{
                $lokasi_id = $request->lokasi_id;
            }

            $penugasan = new Tugas();
            $penugasan->kategori = $request->kategori;
            $penugasan->judul_tugas = $request->judul_tugas;
            $penugasan->keterangan = $request->keterangan;
            $penugasan->status = $request->status;
            $penugasan->petugas_id = $request->petugas_id;
            $penugasan->pengaduan_id = $request->pengaduan_id;
            $penugasan->jenis_id = $request->jenis_id;
            $penugasan->lokasi_id = $lokasi_id;
            $penugasan->save();

            if($request->pengaduan_id){
                $pengaduan = Pengaduan::findOrFail($request->pengaduan_id);
                $pengaduan->status = 1;
                $pengaduan->save();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Penugasan berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Penugasan gagal disimpan',
            ]);
        }
    }

    public function data_penugasan()
    {
        $data = Tugas::with(['petugas', 'pengaduan', 'jenis', 'lokasi'])->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function data_tugas()
    {
        $tugas = Tugas::where(['petugas_id' => auth()->user()->id, 'status' => 1])->with('lokasi','jenis','pengaduan')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tugas,
        ]);
    }

    public function tugas_detail($id)
    {
        $tugas = Tugas::where('id', $id)->with('lokasi','jenis','pengaduan','petugas')->first();

        return response()->json([
            'status' => 'success',
            'data' => $tugas,
        ]);
    }

    public function submit_laporan(StoreLaporanRequest $request)
    {
        try {
            $lokasi = Lokasi::where('nama_lokasi', $request->nama_lokasi)->first();

            if($lokasi){
                $lokasi_id = $lokasi->id;
            }else{
                $lokasi = new Lokasi();
                $lokasi->nama_lokasi = $request->nama_lokasi;
                $lokasi->kelurahan = $request->kelurahan;
                $lokasi->kecamatan = $request->kecamatan;
                $lokasi->kota = $request->kota;
                $lokasi->provinsi = $request->provinsi;
                $lokasi->kodepos = $request->kodepos;
                $lokasi->latitude = $request->latitude;
                $lokasi->longitude = $request->longitude;
                $lokasi->save();

                $lokasi_id = $lokasi->id;
            }

            $laporan = new Laporan();
            $laporan->deskripsi = $request->deskripsi;
            $laporan->lokasi_id = $lokasi_id;
            $laporan->tugas_id = $request->tugas_id;
            $laporan->save();

            if ($request->input('foto', false)) {
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
            }

            $tugas = Tugas::findOrFail($request->tugas_id);
            $tugas->status = 2;
            $tugas->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Laporan berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Laporan gagal disimpan',
            ]);
        }
    }

    public function riwayat_tugas()
    {
        $tugas = Tugas::where(['petugas_id' => auth()->user()->id, 'status' => 2])->with('lokasi','jenis','pengaduan')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tugas,
        ]);
    }
}
