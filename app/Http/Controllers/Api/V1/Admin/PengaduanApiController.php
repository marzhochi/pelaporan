<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\UpdatePengaduanRequest;
use App\Http\Resources\Admin\PengaduanResource;
use App\Models\Pengaduan;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengaduanApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('pengaduan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PengaduanResource(Pengaduan::with(['lokasi'])->get());
    }

    public function store(StorePengaduanRequest $request)
    {
        $pengaduan = Pengaduan::create($request->all());

        foreach ($request->input('foto', []) as $file) {
            $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
        }

        return (new PengaduanResource($pengaduan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pengaduan $pengaduan)
    {
        abort_if(Gate::denies('pengaduan_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new PengaduanResource($pengaduan->load(['lokasi']));
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

        return (new PengaduanResource($pengaduan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Pengaduan $pengaduan)
    {
        abort_if(Gate::denies('pengaduan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pengaduan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
