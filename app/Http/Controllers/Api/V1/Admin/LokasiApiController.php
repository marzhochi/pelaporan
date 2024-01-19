<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Http\Resources\Admin\LokasiResource;
use App\Models\Lokasi;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LokasiApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lokasi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LokasiResource(Lokasi::all());
    }

    public function store(StoreLokasiRequest $request)
    {
        $lokasi = Lokasi::create($request->all());

        return (new LokasiResource($lokasi))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Lokasi $lokasi)
    {
        abort_if(Gate::denies('lokasi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new LokasiResource($lokasi);
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        $lokasi->update($request->all());

        return (new LokasiResource($lokasi))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Lokasi $lokasi)
    {
        abort_if(Gate::denies('lokasi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasi->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
