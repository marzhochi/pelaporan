@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.kategori.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.kategoris.update", [$kategori->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nama_kategori">{{ trans('cruds.kategori.fields.nama_kategori') }}</label>
                <input class="form-control {{ $errors->has('nama_kategori') ? 'is-invalid' : '' }}" type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                @if($errors->has('nama_kategori'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama_kategori') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.kategori.fields.nama_kategori_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection