@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.pengaduan.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pengaduans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.nama_lengkap') }}
                        </th>
                        <td>
                            {{ $pengaduan->nama_lengkap }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.no_telepon') }}
                        </th>
                        <td>
                            {{ $pengaduan->no_telepon }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.judul_pengaduan') }}
                        </th>
                        <td>
                            {{ $pengaduan->judul_pengaduan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.keterangan') }}
                        </th>
                        <td>
                            {{ $pengaduan->keterangan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.foto') }}
                        </th>
                        <td>
                            @foreach($pengaduan->foto as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                    <img src="{{ $media->getUrl('thumb') }}">
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.latlang') }}
                        </th>
                        <td>
                            {{ $pengaduan->latlang }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.lokasi') }}
                        </th>
                        <td>
                            {{ $pengaduan->lokasi->nama_lokasi ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.pengaduan.fields.created_at') }}
                        </th>
                        <td>
                            {{ $pengaduan->created_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.pengaduans.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection