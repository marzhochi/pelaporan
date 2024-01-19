<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLaporanRequest;
use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\Tugar;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('laporan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $laporans = Laporan::with(['lokasi', 'tugas', 'media'])->get();

        return view('admin.laporans.index', compact('laporans'));
    }

    public function create()
    {
        abort_if(Gate::denies('laporan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasis = Lokasi::pluck('nama_lokasi', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugas = Tugar::pluck('judul_tugas', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.laporans.create', compact('lokasis', 'tugas'));
    }

    public function store(StoreLaporanRequest $request)
    {
        $laporan = Laporan::create($request->all());

        foreach ($request->input('foto', []) as $file) {
            $laporan->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $laporan->id]);
        }

        return redirect()->route('admin.laporans.index');
    }

    public function edit(Laporan $laporan)
    {
        abort_if(Gate::denies('laporan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasis = Lokasi::pluck('nama_lokasi', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugas = Tugar::pluck('judul_tugas', 'id')->prepend(trans('global.pleaseSelect'), '');

        $laporan->load('lokasi', 'tugas');

        return view('admin.laporans.edit', compact('laporan', 'lokasis', 'tugas'));
    }

    public function update(UpdateLaporanRequest $request, Laporan $laporan)
    {
        $laporan->update($request->all());

        if (count($laporan->foto) > 0) {
            foreach ($laporan->foto as $media) {
                if (! in_array($media->file_name, $request->input('foto', []))) {
                    $media->delete();
                }
            }
        }
        $media = $laporan->foto->pluck('file_name')->toArray();
        foreach ($request->input('foto', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $laporan->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
            }
        }

        return redirect()->route('admin.laporans.index');
    }

    public function show(Laporan $laporan)
    {
        abort_if(Gate::denies('laporan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $laporan->load('lokasi', 'tugas');

        return view('admin.laporans.show', compact('laporan'));
    }

    public function destroy(Laporan $laporan)
    {
        abort_if(Gate::denies('laporan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $laporan->delete();

        return back();
    }

    public function massDestroy(MassDestroyLaporanRequest $request)
    {
        $laporans = Laporan::find(request('ids'));

        foreach ($laporans as $laporan) {
            $laporan->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('laporan_create') && Gate::denies('laporan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Laporan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
