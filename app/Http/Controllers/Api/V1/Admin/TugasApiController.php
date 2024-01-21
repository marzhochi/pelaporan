<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTugasRequest;
use App\Http\Requests\UpdateTugasRequest;
use App\Http\Resources\Admin\TugasResource;
use App\Models\Tugas;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TugasApiController extends Controller
{
    public function index()
    {
        return new TugasResource(Tugas::with(['petugas', 'pengaduan', 'kategori'])->get());
    }

    public function store(StoreTugasRequest $request)
    {
        $tugas = Tugas::create($request->all());

        return (new TugasResource($tugas))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tugas $tugas)
    {
        return new TugasResource($tugas->load(['petugas', 'pengaduan', 'kategori']));
    }

    public function update(UpdateTugasRequest $request, Tugas $tugas)
    {
        $tugas->update($request->all());

        return (new TugasResource($tugas))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tugas $tugas)
    {
        $tugas->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
