<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Http\Resources\Admin\KategoriResource;
use App\Models\Kategori;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KategoriApiController extends Controller
{
    public function index()
    {
        return new KategoriResource(Kategori::all());
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = Kategori::create($request->all());

        return (new KategoriResource($kategori))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Kategori $kategori)
    {
        return new KategoriResource($kategori);
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $kategori->update($request->all());

        return (new KategoriResource($kategori))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
