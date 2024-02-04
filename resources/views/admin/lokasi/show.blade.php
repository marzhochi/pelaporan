@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.lokasi.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lokasi.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.lokasi.fields.nama_jalan') }}
                        </th>
                        <td>
                            {{ $lokasi->nama_jalan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lokasi.fields.kelurahan') }}
                        </th>
                        <td>
                            {{ $lokasi->kelurahan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lokasi.fields.kecamatan') }}
                        </th>
                        <td>
                            {{ $lokasi->kecamatan }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lokasi.fields.kota') }}
                        </th>
                        <td>
                            {{ $lokasi->kota }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lokasi.fields.provinsi') }}
                        </th>
                        <td>
                            {{ $lokasi->provinsi }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.lokasi.fields.kodepos') }}
                        </th>
                        <td>
                            {{ $lokasi->kodepos }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.lokasi.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
