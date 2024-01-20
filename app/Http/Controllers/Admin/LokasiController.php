<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLokasiRequest;
use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Models\Lokasi;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasi = Lokasi::all();

        return view('admin.lokasi.index', compact('lokasi'));
    }

    public function create()
    {
        return view('admin.lokasi.create');
    }

    public function store(StoreLokasiRequest $request)
    {
        $lokasi = Lokasi::create($request->all());

        return redirect()->route('admin.lokasi.index');
    }

    public function edit(Lokasi $lokasi)
    {
        return view('admin.lokasi.edit', compact('lokasi'));
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        $lokasi->update($request->all());

        return redirect()->route('admin.lokasi.index');
    }

    public function show(Lokasi $lokasi)
    {
        return view('admin.lokasi.show', compact('lokasi'));
    }

    public function destroy(Lokasi $lokasi)
    {
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
