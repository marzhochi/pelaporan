<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyLaporanRequest;
use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Models\Laporan;
use App\Models\Lokasi;
use App\Models\Tugas;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class LaporanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $laporan = Laporan::with(['lokasi', 'tugas', 'media'])->get();

        return view('admin.laporan.index', compact('laporan'));
    }

    public function create()
    {
        $lokasi = Lokasi::pluck('nama_jalan', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugas = Tugas::pluck('judul_tugas', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.laporan.create', compact('lokasi', 'tugas'));
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

        return redirect()->route('admin.laporan.index');
    }

    public function edit(Laporan $laporan)
    {
        $lokasi = Lokasi::pluck('nama_jalan', 'id')->prepend(trans('global.pleaseSelect'), '');

        $tugas = Tugas::pluck('judul_tugas', 'id')->prepend(trans('global.pleaseSelect'), '');

        $laporan->load('lokasi', 'tugas');

        return view('admin.laporan.edit', compact('laporan', 'lokasi', 'tugas'));
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

        return redirect()->route('admin.laporan.index');
    }

    public function show(Laporan $laporan)
    {
        $laporan->load('lokasi', 'tugas');

        return view('admin.laporan.show', compact('laporan'));
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();

        return back();
    }

    public function massDestroy(MassDestroyLaporanRequest $request)
    {
        $laporan = Laporan::find(request('ids'));

        foreach ($laporan as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Laporan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
