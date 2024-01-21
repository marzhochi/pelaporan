@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Tambah Pengguna
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pengguna.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="nama_lengkap">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('nama_lengkap') ? 'is-invalid' : '' }}" type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', '') }}" required>
                @if($errors->has('nama_lengkap'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nama_lengkap') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="nip">{{ trans('cruds.user.fields.nip') }}</label>
                <input class="form-control {{ $errors->has('nip') ? 'is-invalid' : '' }}" type="number" name="nip" id="nip" value="{{ old('nip', '') }}" step="1">
                @if($errors->has('nip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.nip_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="golongan">{{ trans('cruds.user.fields.golongan') }}</label>
                <input class="form-control {{ $errors->has('golongan') ? 'is-invalid' : '' }}" type="text" name="golongan" id="golongan" value="{{ old('golongan', '') }}">
                @if($errors->has('golongan'))
                    <div class="invalid-feedback">
                        {{ $errors->first('golongan') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.golongan_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.jenis_kelamin') }}</label>
                @foreach(App\Models\User::JENIS_KELAMIN_RADIO as $key => $label)
                    <div class="form-check {{ $errors->has('jenis_kelamin') ? 'is-invalid' : '' }}">
                        <input class="form-check-input" type="radio" id="jenis_kelamin_{{ $key }}" name="jenis_kelamin" value="{{ $key }}" {{ old('jenis_kelamin', '') === (string) $key ? 'checked' : '' }}>
                        <label class="form-check-label" for="jenis_kelamin_{{ $key }}">{{ $label }}</label>
                    </div>
                @endforeach
                @if($errors->has('jenis_kelamin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('jenis_kelamin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.jenis_kelamin_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="no_telp">{{ trans('cruds.user.fields.no_telp') }}</label>
                <input class="form-control {{ $errors->has('no_telp') ? 'is-invalid' : '' }}" type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', '') }}">
                @if($errors->has('no_telp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('no_telp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.no_telp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="avatar">{{ trans('cruds.user.fields.avatar') }}</label>
                <div class="needsclick dropzone {{ $errors->has('avatar') ? 'is-invalid' : '' }}" id="avatar-dropzone">
                </div>
                @if($errors->has('avatar'))
                    <div class="invalid-feedback">
                        {{ $errors->first('avatar') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.avatar_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">Role</label>
                <select class="form-control select2 {{ $errors->has('role') ? 'is-invalid' : '' }}" name="role" id="role" required>
                    <option value="">Pilih Role</option>
                    <option value="1">Kepala Petugas</option>
                    <option value="2">Petugas Lapangan</option>
                </select>
                @if($errors->has('role'))
                    <div class="invalid-feedback">
                        {{ $errors->first('role') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
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
    Dropzone.options.avatarDropzone = {
    url: '{{ route('admin.pengguna.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
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
      $('form').find('input[name="avatar"]').remove()
      $('form').append('<input type="hidden" name="avatar" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="avatar"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($user) && $user->avatar)
      var file = {!! json_encode($user->avatar) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="avatar" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
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
