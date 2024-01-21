<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPengaduanRequest;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\UpdatePengaduanRequest;
use App\Models\Lokasi;
use App\Models\Pengaduan;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PengaduanController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $pengaduan = Pengaduan::with(['lokasi', 'media'])->get();

        return view('admin.pengaduan.index', compact('pengaduan'));
    }

    public function create()
    {
        $lokasi = Lokasi::pluck('nama_lokasi', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pengaduan.create', compact('lokasi'));
    }

    public function store(StorePengaduanRequest $request)
    {
        $pengaduan = Pengaduan::create($request->all());

        if ($request->input('foto', false)) {
            $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $pengaduan->id]);
        }

        return redirect()->route('admin.pengaduan.index');
    }

    public function edit(Pengaduan $pengaduan)
    {
        $lokasi = Lokasi::pluck('nama_lokasi', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pengaduan->load('lokasi');

        return view('admin.pengaduan.edit', compact('lokasi', 'pengaduan'));
    }

    public function update(UpdatePengaduanRequest $request, Pengaduan $pengaduan)
    {
        $pengaduan->update($request->all());

        if ($request->input('foto', false)) {
            if (! $pengaduan->foto || $request->input('foto') !== $pengaduan->foto->file_name) {
                if ($pengaduan->foto) {
                    $pengaduan->foto->delete();
                }
                $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
            }
        } elseif ($pengaduan->foto) {
            $pengaduan->foto->delete();
        }

        return redirect()->route('admin.pengaduan.index');
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load('lokasi');

        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return back();
    }

    public function massDestroy(MassDestroyPengaduanRequest $request)
    {
        $pengaduan = Pengaduan::find(request('ids'));

        foreach ($pengaduan as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Pengaduan();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
