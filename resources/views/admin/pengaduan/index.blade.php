@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.pengaduan.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.pengaduan.title_singular') }}
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('cruds.pengaduan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Pengaduan">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.nama_lengkap') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.no_telepon') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.judul_pengaduan') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.keterangan') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.foto') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.latlang') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.lokasi') }}
                        </th>
                        <th>
                            {{ trans('cruds.pengaduan.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengaduan as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td>
                        </td>
                        <td>
                            {{ $item->nama_lengkap ?? '' }}
                        </td>
                        <td>
                            {{ $item->no_telepon ?? '' }}
                        </td>
                        <td>
                            {{ $item->judul_pengaduan ?? '' }}
                        </td>
                        <td>
                            {{ $item->keterangan ?? '' }}
                        </td>
                        <td>
                            @if($item->foto)
                            <a href="{{ $item->foto->getUrl() }}" target="_blank" style="display: inline-block">
                                <img src="{{ $item->foto->getUrl('thumb') }}">
                            </a>
                            @endif
                        </td>
                        <td>
                            {{ $item->latlang ?? '' }}
                        </td>
                        <td>
                            {{ $item->lokasi->nama_jalan ?? '' }}
                        </td>
                        <td>
                            {{ $item->created_at ?? '' }}
                        </td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.pengaduan.show', $item->id) }}">
                                {{ trans('global.view') }}
                            </a>
                            <a class="btn btn-xs btn-info" href="{{ route('admin.pengaduan.edit', $item->id) }}">
                                {{ trans('global.edit') }}
                            </a>
                            <form action="{{ route('admin.pengaduan.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.pengaduan.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({
                    selected: true
                }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: {
                            'x-csrf-token': _token
                        },
                        method: 'POST',
                        url: config.url,
                        data: {
                            ids: ids,
                            _method: 'DELETE'
                        }
                    })
                    .done(function () {
                        location.reload()
                    })
                }
            }
        }
        dtButtons.push(deleteButton)

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [
                [8, 'desc']
            ],
            pageLength: 10,
        });
        let table = $('.datatable-Pengaduan:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection
