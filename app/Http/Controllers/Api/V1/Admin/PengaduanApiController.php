<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\UpdatePengaduanRequest;
use App\Http\Resources\Admin\PengaduanResource;
use App\Models\Pengaduan;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengaduanApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        return new PengaduanResource(Pengaduan::with(['lokasi'])->get());
    }

    public function store(StorePengaduanRequest $request)
    {
        $pengaduan = Pengaduan::create($request->all());

        if ($request->input('foto', false)) {
            $pengaduan->addMedia(storage_path('tmp/uploads/' . basename($request->input('foto'))))->toMediaCollection('foto');
        }

        return (new PengaduanResource($pengaduan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pengaduan $pengaduan)
    {
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
        $pengaduan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
