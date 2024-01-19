<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyKategoriRequest;
use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Models\Kategori;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class KategoriController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('kategori_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kategori = Kategori::all();

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        abort_if(Gate::denies('kategori_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.kategori.create');
    }

    public function store(StoreKategoriRequest $request)
    {
        $kategori = Kategori::create($request->all());

        return redirect()->route('admin.kategori.index');
    }

    public function edit(Kategori $kategori)
    {
        abort_if(Gate::denies('kategori_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(UpdateKategoriRequest $request, Kategori $kategori)
    {
        $kategori->update($request->all());

        return redirect()->route('admin.kategori.index');
    }

    public function show(Kategori $kategori)
    {
        abort_if(Gate::denies('kategori_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.kategori.show', compact('kategori'));
    }

    public function destroy(Kategori $kategori)
    {
        abort_if(Gate::denies('kategori_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kategori->delete();

        return back();
    }

    public function massDestroy(MassDestroyKategoriRequest $request)
    {
        $kategori = Kategori::find(request('ids'));

        foreach ($kategori as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
