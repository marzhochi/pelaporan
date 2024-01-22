<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Resources\Admin\PenggunaResource;
use App\Models\Pengguna;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PenggunaApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        return new PenggunaResource(Pengguna::with(['roles'])->get());
    }

    public function store(StorePenggunaRequest $request)
    {
        $user = Pengguna::create($request->all());
        $user->roles()->sync($request->input('roles', []));
        if ($request->input('avatar', false)) {
            $user->addMedia(storage_path('tmp/uploads/' . basename($request->input('avatar'))))->toMediaCollection('avatar');
        }

        return (new PenggunaResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Pengguna $user)
    {
        return new PenggunaResource($user->load(['roles']));
    }

    public function update(UpdatePenggunaRequest $request, Pengguna $user)
    {
        $user->update($request->all());
        $user->roles()->sync($request->input('roles', []));
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

        return (new PenggunaResource($user))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Pengguna $user)
    {
        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
