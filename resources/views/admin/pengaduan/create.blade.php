@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.pengaduan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pengaduan.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nama_lengkap">{{ trans('cruds.pengaduan.fields.nama_lengkap') }}</label>
                <input class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', '') }}" required>
                @if($errors->has('nama_lengkap'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama_lengkap') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.nama_lengkap_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="no_telepon">{{ trans('cruds.pengaduan.fields.no_telepon') }}</label>
                <input class="form-control {{ $errors->has('no_telepon') ? 'is-invalid' : '' }}" type="text" name="no_telepon" id="no_telepon" value="{{ old('no_telepon', '') }}" required>
                @if($errors->has('no_telepon'))
                    <div class="invalid-feedback">
                        {{ $errors->first('no_telepon') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.no_telepon_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="judul_pengaduan">{{ trans('cruds.pengaduan.fields.judul_pengaduan') }}</label>
                <input class="form-control {{ $errors->has('judul_pengaduan') ? 'is-invalid' : '' }}" type="text" name="judul_pengaduan" id="judul_pengaduan" value="{{ old('judul_pengaduan', '') }}" required>
                @if($errors->has('judul_pengaduan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('judul_pengaduan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.judul_pengaduan_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="keterangan">{{ trans('cruds.pengaduan.fields.keterangan') }}</label>
                <input class="form-control {{ $errors->has('keterangan') ? 'is-invalid' : '' }}" type="text" name="keterangan" id="keterangan" value="{{ old('keterangan', '') }}">
                @if($errors->has('keterangan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('keterangan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.keterangan_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="foto">{{ trans('cruds.pengaduan.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('foto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.foto_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="latlang">{{ trans('cruds.pengaduan.fields.latlang') }}</label>
                <input class="form-control {{ $errors->has('latlang') ? 'is-invalid' : '' }}" type="text" name="latlang" id="latlang" value="{{ old('latlang', '') }}" required>
                @if($errors->has('latlang'))
                    <div class="invalid-feedback">
                        {{ $errors->first('latlang') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.latlang_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lokasi_id">{{ trans('cruds.pengaduan.fields.lokasi') }}</label>
                <select class="form-control select2 {{ $errors->has('lokasi') ? 'is-invalid' : '' }}" name="lokasi_id" id="lokasi_id">
                    @foreach($lokasi as $id => $entry)
                        <option value="{{ $id }}" {{ old('lokasi_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('lokasi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('lokasi') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.pengaduan.fields.lokasi_helper') }}</span>
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

@section('scripts')
<script>
    var uploadedFotoMap = {}
Dropzone.options.fotoDropzone = {
    url: '{{ route('admin.pengaduan.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="foto[]" value="' + response.name + '">')
      uploadedFotoMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFotoMap[file.name]
      }
      $('form').find('input[name="foto[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($pengaduan) && $pengaduan->foto)
      var files = {!! json_encode($pengaduan->foto) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="foto[]" value="' + file.file_name + '">')
        }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

</script>
@endsection
