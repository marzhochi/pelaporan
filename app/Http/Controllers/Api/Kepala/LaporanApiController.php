<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreLaporanRequest;
use App\Http\Requests\UpdateLaporanRequest;
use App\Http\Resources\Admin\LaporanResource;
use App\Models\Laporan;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LaporanApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        return new LaporanResource(Laporan::with(['lokasi', 'tugas'])->get());
    }

    public function store(StoreLaporanRequest $request)
    {
        $laporan = Laporan::create($request->all());

        foreach ($request->input('foto', []) as $file) {
            $laporan->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto');
        }

        return (new LaporanResource($laporan))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Laporan $laporan)
    {
        return new LaporanResource($laporan->load(['lokasi', 'tugas']));
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

        return (new LaporanResource($laporan))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Laporan $laporan)
    {
        $laporan->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
