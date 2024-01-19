@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.lokasi.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.lokasi.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nama_lokasi">{{ trans('cruds.lokasi.fields.nama_lokasi') }}</label>
                <input class="form-control {{ $errors->has('nama_lokasi') ? 'is-invalid' : '' }}" type="text" name="nama_lokasi" id="nama_lokasi" value="{{ old('nama_lokasi', '') }}" required>
                @if($errors->has('nama_lokasi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama_lokasi') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lokasi.fields.nama_lokasi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kelurahan">{{ trans('cruds.lokasi.fields.kelurahan') }}</label>
                <input class="form-control {{ $errors->has('kelurahan') ? 'is-invalid' : '' }}" type="text" name="kelurahan" id="kelurahan" value="{{ old('kelurahan', '') }}">
                @if($errors->has('kelurahan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kelurahan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lokasi.fields.kelurahan_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kecamatan">{{ trans('cruds.lokasi.fields.kecamatan') }}</label>
                <input class="form-control {{ $errors->has('kecamatan') ? 'is-invalid' : '' }}" type="text" name="kecamatan" id="kecamatan" value="{{ old('kecamatan', '') }}">
                @if($errors->has('kecamatan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kecamatan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lokasi.fields.kecamatan_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kota">{{ trans('cruds.lokasi.fields.kota') }}</label>
                <input class="form-control {{ $errors->has('kota') ? 'is-invalid' : '' }}" type="text" name="kota" id="kota" value="{{ old('kota', '') }}">
                @if($errors->has('kota'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kota') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lokasi.fields.kota_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="provinsi">{{ trans('cruds.lokasi.fields.provinsi') }}</label>
                <input class="form-control {{ $errors->has('provinsi') ? 'is-invalid' : '' }}" type="text" name="provinsi" id="provinsi" value="{{ old('provinsi', '') }}">
                @if($errors->has('provinsi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('provinsi') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lokasi.fields.provinsi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="kodepos">{{ trans('cruds.lokasi.fields.kodepos') }}</label>
                <input class="form-control {{ $errors->has('kodepos') ? 'is-invalid' : '' }}" type="text" name="kodepos" id="kodepos" value="{{ old('kodepos', '') }}">
                @if($errors->has('kodepos'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kodepos') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.lokasi.fields.kodepos_helper') }}</span>
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
