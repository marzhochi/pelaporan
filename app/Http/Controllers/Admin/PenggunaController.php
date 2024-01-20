<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPenggunaRequest;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;

use App\Models\Pengguna;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class PenggunaController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $users = Pengguna::with(['media'])->get();

        return view('admin.pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(StorePenggunaRequest $request)
    {
        $user = Pengguna::create($request->all());

        if ($request->input('avatar', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('avatar'))))->toMediaCollection('avatar');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $user->id]);
        }

        return redirect()->route('admin.pengguna.index');
    }

    public function edit(Pengguna $user)
    {
        return view('admin.pengguna.edit', compact('user'));
    }

    public function update(UpdatePenggunaRequest $request, Pengguna $user)
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

        return redirect()->route('admin.pengguna.index');
    }

    public function show(Pengguna $user)
    {
        return view('admin.pengguna.show', compact('user'));
    }

    public function destroy(Pengguna $user)
    {
        $user->delete();

        return back();
    }

    public function massDestroy(MassDestroyPenggunaRequest $request)
    {
        $users = Pengguna::find(request('ids'));

        foreach ($users as $user) {
            $user->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        $model         = new Pengguna();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
