<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLokasiRequest;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Models\Lokasi;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LokasiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('lokasi_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasi = Lokasi::all();

        return view('admin.lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        abort_if(Gate::denies('lokasi_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lokasi.create');
    }

    public function store(StoreLokasiRequest $request)
    {
        $lokasi = Lokasi::create($request->all());

        return redirect()->route('admin.lokasi.index');
    }

    public function edit(Lokasi $lokasi)
    {
        abort_if(Gate::denies('lokasi_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        $lokasi->update($request->all());

        return redirect()->route('admin.lokasi.index');
    }

    public function show(Lokasi $lokasi)
    {
        abort_if(Gate::denies('lokasi_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.lokasi.show', compact('lokasi'));
    }

    public function destroy(Lokasi $lokasi)
    {
        abort_if(Gate::denies('lokasi_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $lokasi->delete();

        return back();
    }

    public function massDestroy(MassDestroyLokasiRequest $request)
    {
        $lokasi = Lokasi::find(request('ids'));

        foreach ($lokasi as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
