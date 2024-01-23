<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyJenisRequest;
use App\Http\Requests\StoreJenisRequest;
use App\Http\Requests\UpdateJenisRequest;
use App\Models\Jenis;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JenisController extends Controller
{
    public function index()
    {
        $jenis = Jenis::all();

        return view('admin.jenis.index', compact('jenis'));
    }

    public function create()
    {
        return view('admin.jenis.create');
    }

    public function store(StoreJenisRequest $request)
    {
        $jenis = Jenis::create($request->all());

        return redirect()->route('admin.jenis.index');
    }

    public function edit(Jenis $jenis)
    {
        return view('admin.jenis.edit', compact('jenis'));
    }

    public function update(UpdateJenisRequest $request, Jenis $jenis)
    {
        $jenis->update($request->all());

        return redirect()->route('admin.jenis.index');
    }

    public function show(Jenis $jenis)
    {
        return view('admin.jenis.show', compact('jenis'));
    }

    public function destroy(Jenis $jenis)
    {
        $jenis->delete();

        return back();
    }

    public function massDestroy(MassDestroyJenisRequest $request)
    {
        $jenis = Jenis::find(request('ids'));

        foreach ($jenis as $item) {
            $item->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
