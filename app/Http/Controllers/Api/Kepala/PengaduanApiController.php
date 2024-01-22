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
