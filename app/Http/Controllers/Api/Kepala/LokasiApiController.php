<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Http\Resources\Admin\LokasiResource;
use App\Models\Lokasi;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LokasiApiController extends Controller
{
    public function index()
    {
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
        $lokasi->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
