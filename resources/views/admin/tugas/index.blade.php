@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.tugas.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.tugas.title_singular') }}
        </a>
    </div>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.list') }} {{ trans('cruds.tugas.title_singular') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-tugas">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.petugas') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.pengaduan') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.kategori') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.judul_tugas') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.keterangan') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugas.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tugas as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                        <td>

                        </td>
                        <td>
                            {{ $item->petugas->name ?? '' }}
                        </td>
                        <td>
                            {{ $item->pengaduan->nama_lengkap ?? '' }}
                        </td>
                        <td>
                            {{ $item->kategori->nama_kategori ?? '' }}
                        </td>
                        <td>
                            {{ $item->judul_tugas ?? '' }}
                        </td>
                        <td>
                            {{ $item->keterangan ?? '' }}
                        </td>
                        <td>
                            {{ App\Models\Tugas::STATUS_RADIO[$item->status] ?? '' }}
                        </td>
                        <td>
                            {{ $item->created_at ?? '' }}
                        </td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.tugas.show', $item->id) }}">
                                {{ trans('global.view') }}
                            </a>
                            <a class="btn btn-xs btn-info" href="{{ route('admin.tugas.edit', $item->id) }}">
                                {{ trans('global.edit') }}
                            </a>
                            <form action="{{ route('admin.tugas.destroy', $item->id) }}" method="POST"
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
            url: "{{ route('admin.tugas.massDestroy') }}",
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
                [7, 'desc']
            ],
            pageLength: 10,
        });
        let table = $('.datatable-tugas:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection
