<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPengaduanRequest;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\UpdatePengaduanRequest;
use App\Models\Lokasi;
use App\Models\Pengaduan;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PengaduanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('pengaduan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pengaduans = Pengaduan::with(['lokasi', 'media'])->get();

        return view('admin.pengaduans.index', compact('pengaduans'));
    }

    public function create()
    {
        abort_if(Gate::denies('pengaduan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasis = Lokasi::pluck('nama_lokasi', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pengaduans.create', compact('lokasis'));
    }

    public function store(StorePengaduanRequest $request)
    {
        $pengaduan = Pengaduan::create($request->all());

        foreach ($request->input('foto', []) as $file) {
            $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $pengaduan->id]);
        }

        return redirect()->route('admin.pengaduans.index');
    }

    public function edit(Pengaduan $pengaduan)
    {
        abort_if(Gate::denies('pengaduan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasis = Lokasi::pluck('nama_lokasi', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduan->load('lokasi');

        return view('admin.pengaduans.edit', compact('lokasis', 'pengaduan'));
    }

    public function update(UpdatePengaduanRequest $request, Pengaduan $pengaduan)
    {
        $pengaduan->update($request->all());

        if (count($pengaduan->foto) > 0) {
            foreach ($pengaduan->foto as $media) {
                if (! in_array($media->file_name, $request->input('foto', []))) {
                    $media->delete();
                }
            }
        }
        $media = $pengaduan->foto->pluck('file_name')->toArray();
        foreach ($request->input('foto', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
            }
        }

        return redirect()->route('admin.pengaduans.index');
    }

    public function show(Pengaduan $pengaduan)
    {
        abort_if(Gate::denies('pengaduan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pengaduan->load('lokasi');

        return view('admin.pengaduans.show', compact('pengaduan'));
    }

    public function destroy(Pengaduan $pengaduan)
    {
        abort_if(Gate::denies('pengaduan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pengaduan->delete();

        return back();
    }

    public function massDestroy(MassDestroyPengaduanRequest $request)
    {
        $pengaduans = Pengaduan::find(request('ids'));

        foreach ($pengaduans as $pengaduan) {
            $pengaduan->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pengaduan_create') && Gate::denies('pengaduan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pengaduan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
