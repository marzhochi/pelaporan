<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTugasRequest;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Tugas;
use App\Models\Pengguna;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with(['petugas', 'pengaduan', 'kategori'])->get();

        return view('admin.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $petugas = Pengguna::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduan = Pengaduan::pluck('judul_pengaduan', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kategori = Kategori::pluck('nama_kategori', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tugas.create', compact('kategori', 'pengaduan', 'petugas'));
    }

    public function store(StoreTugasRequest $request)
    {
        $tugas = Tugas::create($request->all());

        return redirect()->route('admin.tugas.index');
    }

    public function edit(Tugas $tugas)
    {
        $petugas = Pengguna::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduan = Pengaduan::pluck('nama_lengkap', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kategori = Kategori::pluck('nama_kategori', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugas->load('petugas', 'pengaduan', 'kategori');

        return view('admin.tugas.edit', compact('kategori', 'pengaduan', 'petugas', 'tugas'));
    }

    public function update(UpdateTugasRequest $request, Tugas $tugas)
    {
        $tugas->update($request->all());

        return redirect()->route('admin.tugas.index');
    }

    public function show(Tugas $tugas)
    {
        $tugas->load('petugas', 'pengaduan', 'kategori');

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
