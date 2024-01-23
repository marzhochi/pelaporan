@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.jenis.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.jenis.update", [$jenis->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nama_jenis">{{ trans('cruds.jenis.fields.nama_jenis') }}</label>
                <input class="form-control {{ $errors->has('nama_jenis') ? 'is-invalid' : '' }}" type="text" name="nama_jenis" id="nama_jenis" value="{{ old('nama_jenis', $jenis->nama_jenis) }}" required>
                @if($errors->has('nama_jenis'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama_jenis') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.jenis.fields.nama_jenis_helper') }}</span>
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
