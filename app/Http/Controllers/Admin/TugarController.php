<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTugarRequest;
use App\Http\Requests\StoreTugarRequest;
use App\Http\Requests\UpdateTugarRequest;
use App\Models\Kategori;
use App\Models\Pengaduan;
use App\Models\Tugar;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TugarController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tugar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tugars = Tugar::with(['petugas', 'pengaduan', 'kategori'])->get();

        return view('admin.tugars.index', compact('tugars'));
    }

    public function create()
    {
        abort_if(Gate::denies('tugar_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $petugas = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduans = Pengaduan::pluck('judul_pengaduan', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kategoris = Kategori::pluck('nama_kategori', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.tugars.create', compact('kategoris', 'pengaduans', 'petugas'));
    }

    public function store(StoreTugarRequest $request)
    {
        $tugar = Tugar::create($request->all());

        return redirect()->route('admin.tugars.index');
    }

    public function edit(Tugar $tugar)
    {
        abort_if(Gate::denies('tugar_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $petugas = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduans = Pengaduan::pluck('nama_lengkap', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kategoris = Kategori::pluck('nama_kategori', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugar->load('petugas', 'pengaduan', 'kategori');

        return view('admin.tugars.edit', compact('kategoris', 'pengaduans', 'petugas', 'tugar'));
    }

    public function update(UpdateTugarRequest $request, Tugar $tugar)
    {
        $tugar->update($request->all());

        return redirect()->route('admin.tugars.index');
    }

    public function show(Tugar $tugar)
    {
        abort_if(Gate::denies('tugar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tugar->load('petugas', 'pengaduan', 'kategori');

        return view('admin.tugars.show', compact('tugar'));
    }

    public function destroy(Tugar $tugar)
    {
        abort_if(Gate::denies('tugar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tugar->delete();

        return back();
    }

    public function massDestroy(MassDestroyTugarRequest $request)
    {
        $tugars = Tugar::find(request('ids'));

        foreach ($tugars as $tugar) {
            $tugar->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
