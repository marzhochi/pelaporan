@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tugas.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tugas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.petugas') }}
                        </th>
                        <td>
                            {{ $tugas->petugas->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.pengaduan') }}
                        </th>
                        <td>
                            {{ $tugas->pengaduan->nama_lengkap ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.kategori') }}
                        </th>
                        <td>
                            {{ $tugas->kategori->nama_kategori ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.judul_tugas') }}
                        </th>
                        <td>
                            {{ $tugas->judul_tugas }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.keterangan') }}
                        </th>
                        <td>
                            {{ $tugas->keterangan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\Tugas::STATUS_RADIO[$tugas->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tugas.fields.created_at') }}
                        </th>
                        <td>
                            {{ $tugas->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tugas.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
