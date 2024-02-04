<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPetugasRequest;
use App\Http\Requests\StorePetugasRequest;
use App\Http\Requests\UpdatePetugasRequest;

use App\Models\Petugas;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PetugasController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $users = Petugas::with(['media'])->get();

        return view('admin.petugas.index', compact('users'));
    }

    public function create()
    {
        return view('admin.petugas.create');
    }

    public function store(StorePetugasRequest $request)
    {
        $user = Petugas::create($request->all());

        if ($request->input('avatar', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('avatar'))))->toMediaCollection('avatar');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }

        return redirect()->route('admin.petugas.index');
    }

    public function edit(Petugas $user)
    {
        return view('admin.petugas.edit', compact('user'));
    }

    public function update(UpdatePetugasRequest $request, Petugas $user)
    {
        $user->update($request->all());

        if ($request->input('avatar', false)) {
            if (! $user->avatar || $request->input('avatar') !== $user->avatar->file_name) {
                if ($user->avatar) {
                    $user->avatar->delete();
                }
                $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('avatar'))))->toMediaCollection('avatar');
            }
        } elseif ($user->avatar) {
            $user->avatar->delete();
        }

        return redirect()->route('admin.petugas.index');
    }

    public function show(Petugas $user)
    {
        return view('admin.petugas.show', compact('user'));
    }

    public function destroy(Petugas $user)
    {
        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyPetugasRequest $request)
    {
        $users = Petugas::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Petugas();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
