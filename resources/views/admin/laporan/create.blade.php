@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.laporan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.laporan.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="deskripsi">{{ trans('cruds.laporan.fields.deskripsi') }}</label>
                <input class="form-control {{ $errors->has('deskripsi') ? 'is-invalid' : '' }}" type="text" name="deskripsi" id="deskripsi" value="{{ old('deskripsi', '') }}" required>
                @if($errors->has('deskripsi'))
                    <div class="invalid-feedback">
                        {{ $errors->first('deskripsi') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.laporan.fields.deskripsi_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="foto">{{ trans('cruds.laporan.fields.foto') }}</label>
                <div class="needsclick dropzone {{ $errors->has('foto') ? 'is-invalid' : '' }}" id="foto-dropzone">
                </div>
                @if($errors->has('foto'))
                    <div class="invalid-feedback">
                        {{ $errors->first('foto') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.laporan.fields.foto_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lokasi_id">{{ trans('cruds.laporan.fields.lokasi') }}</label>
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
                <span class="help-block">{{ trans('cruds.laporan.fields.lokasi_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tugas_id">{{ trans('cruds.laporan.fields.tugas') }}</label>
                <select class="form-control select2 {{ $errors->has('tugas') ? 'is-invalid' : '' }}" name="tugas_id" id="tugas_id">
                    @foreach($tugas as $id => $entry)
                        <option value="{{ $id }}" {{ old('tugas_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('tugas'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tugas') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.laporan.fields.tugas_helper') }}</span>
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
    url: '{{ route('admin.laporan.storeMedia') }}',
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
@if(isset($laporan) && $laporan->foto)
      var files = {!! json_encode($laporan->foto) !!}
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
