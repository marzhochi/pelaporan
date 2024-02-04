<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTugasRequest;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Models\Jenis;
use App\Models\Pengaduan;
use App\Models\Tugas;
use App\Models\Petugas;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['petugas', 'pengaduan', 'jenis'])->get();

        return view('admin.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $petugas = Petugas::pluck('nama_lengkap', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduan = Pengaduan::pluck('judul_pengaduan', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenis = Jenis::pluck('nama_jenis', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tugas.create', compact('jenis', 'pengaduan', 'petugas'));
    }

    public function store(StoreTugasRequest $request)
    {
        $tugas = Tugas::create($request->all());

        $pengaduan = Pengaduan::find($request->pengaduan_id);
        $pengaduan->status = 1;
        $pengaduan->update();

        return redirect()->route('admin.tugas.index');
    }

    public function edit(Tugas $tugas)
    {
        $petugas = Petugas::pluck('nama_lengkap', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduan = Pengaduan::pluck('judul_pengaduan', 'id')->prepend(trans('global.pleaseSelect'), '');

        $jenis = Jenis::pluck('nama_jenis', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugas->load('petugas', 'pengaduan', 'jenis');

        return view('admin.tugas.edit', compact('jenis', 'pengaduan', 'petugas', 'tugas'));
    }

    public function update(UpdateTugasRequest $request, Tugas $tugas)
    {
        $tugas->update($request->all());

        return redirect()->route('admin.tugas.index');
    }

    public function show(Tugas $tugas)
    {
        $tugas->load('petugas', 'pengaduan', 'jenis');

        return view('admin.tugas.show', compact('tugas'));
    }

    public function destroy(Tugas $tugas)
    {
        $tugas->delete();

        return back();
    }

    public function massDestroy(MassDestroyTugasRequest $request)
    {
        $tugas = Tugas::find(request('ids'));

        foreach ($tugas as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
