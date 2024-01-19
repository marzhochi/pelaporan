@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.tugar.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tugars.update", [$tugar->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="petugas_id">{{ trans('cruds.tugar.fields.petugas') }}</label>
                <select class="form-control select2 {{ $errors->has('petugas') ? 'is-invalid' : '' }}" name="petugas_id" id="petugas_id" required>
                    @foreach($petugas as $id => $entry)
                        <option value="{{ $id }}" {{ (old('petugas_id') ? old('petugas_id') : $tugar->petugas->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('petugas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('petugas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tugar.fields.petugas_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="pengaduan_id">{{ trans('cruds.tugar.fields.pengaduan') }}</label>
                <select class="form-control select2 {{ $errors->has('pengaduan') ? 'is-invalid' : '' }}" name="pengaduan_id" id="pengaduan_id" required>
                    @foreach($pengaduans as $id => $entry)
                        <option value="{{ $id }}" {{ (old('pengaduan_id') ? old('pengaduan_id') : $tugar->pengaduan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('pengaduan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('pengaduan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tugar.fields.pengaduan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="kategori_id">{{ trans('cruds.tugar.fields.kategori') }}</label>
                <select class="form-control select2 {{ $errors->has('kategori') ? 'is-invalid' : '' }}" name="kategori_id" id="kategori_id" required>
                    @foreach($kategoris as $id => $entry)
                        <option value="{{ $id }}" {{ (old('kategori_id') ? old('kategori_id') : $tugar->kategori->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('kategori'))
                    <div class="invalid-feedback">
                        {{ $errors->first('kategori') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tugar.fields.kategori_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="judul_tugas">{{ trans('cruds.tugar.fields.judul_tugas') }}</label>
                <input class="form-control {{ $errors->has('judul_tugas') ? 'is-invalid' : '' }}" type="text" name="judul_tugas" id="judul_tugas" value="{{ old('judul_tugas', $tugar->judul_tugas) }}" required>
                @if($errors->has('judul_tugas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('judul_tugas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tugar.fields.judul_tugas_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="keterangan">{{ trans('cruds.tugar.fields.keterangan') }}</label>
                <input class="form-control {{ $errors->has('keterangan') ? 'is-invalid' : '' }}" type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', $tugar->keterangan) }}">
                @if($errors->has('keterangan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('keterangan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tugar.fields.keterangan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.tugar.fields.status') }}</label>
                @foreach(App\Models\Tugar::STATUS_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('status') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="status_{{ $key }}" name="status" value="{{ $key }}" {{ old('status', $tugar->status) === (string) $key ? 'checked' : '' }} required>
                        <label class="form-check-label" for="status_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.tugar.fields.status_helper') }}</span>
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