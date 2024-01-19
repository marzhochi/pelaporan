@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tugar.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tugars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.petugas') }}
                        </th>
                        <td>
                            {{ $tugar->petugas->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.pengaduan') }}
                        </th>
                        <td>
                            {{ $tugar->pengaduan->nama_lengkap ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.kategori') }}
                        </th>
                        <td>
                            {{ $tugar->kategori->nama_kategori ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.judul_tugas') }}
                        </th>
                        <td>
                            {{ $tugar->judul_tugas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.keterangan') }}
                        </th>
                        <td>
                            {{ $tugar->keterangan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Tugar::STATUS_RADIO[$tugar->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugar.fields.created_at') }}
                        </th>
                        <td>
                            {{ $tugar->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tugars.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection