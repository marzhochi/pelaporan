<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJenisRequest;
use App\Http\Requests\UpdateJenisRequest;
use App\Http\Resources\Admin\JenisResource;
use App\Models\Jenis;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JenisApiController extends Controller
{
    public function index()
    {
        return new JenisResource(Jenis::all());
    }

    public function store(StoreJenisRequest $request)
    {
        $jenis = Jenis::create($request->all());

        return (new JenisResource($jenis))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Jenis $jenis)
    {
        return new JenisResource($jenis);
    }

    public function update(UpdateJenisRequest $request, Jenis $jenis)
    {
        $jenis->update($request->all());

        return (new JenisResource($jenis))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Jenis $jenis)
    {
        $jenis->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
