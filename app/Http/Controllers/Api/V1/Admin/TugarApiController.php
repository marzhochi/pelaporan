<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTugarRequest;
use App\Http\Requests\UpdateTugarRequest;
use App\Http\Resources\Admin\TugarResource;
use App\Models\Tugar;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TugarApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('tugar_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TugarResource(Tugar::with(['petugas', 'pengaduan', 'kategori'])->get());
    }

    public function store(StoreTugarRequest $request)
    {
        $tugar = Tugar::create($request->all());

        return (new TugarResource($tugar))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Tugar $tugar)
    {
        abort_if(Gate::denies('tugar_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TugarResource($tugar->load(['petugas', 'pengaduan', 'kategori']));
    }

    public function update(UpdateTugarRequest $request, Tugar $tugar)
    {
        $tugar->update($request->all());

        return (new TugarResource($tugar))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Tugar $tugar)
    {
        abort_if(Gate::denies('tugar_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $tugar->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
