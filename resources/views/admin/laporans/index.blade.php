@extends('layouts.admin')
@section('content')
@can('laporan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.laporans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.laporan.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.laporan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Laporan">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.laporan.fields.deskripsi') }}
                        </th>
                        <th>
                            {{ trans('cruds.laporan.fields.foto') }}
                        </th>
                        <th>
                            {{ trans('cruds.laporan.fields.lokasi') }}
                        </th>
                        <th>
                            {{ trans('cruds.laporan.fields.tugas') }}
                        </th>
                        <th>
                            {{ trans('cruds.laporan.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporans as $key => $laporan)
                        <tr data-entry-id="{{ $laporan->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $laporan->deskripsi ?? '' }}
                            </td>
                            <td>
                                @foreach($laporan->foto as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                {{ $laporan->lokasi->nama_lokasi ?? '' }}
                            </td>
                            <td>
                                {{ $laporan->tugas->judul_tugas ?? '' }}
                            </td>
                            <td>
                                {{ $laporan->created_at ?? '' }}
                            </td>
                            <td>
                                @can('laporan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.laporans.show', $laporan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('laporan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.laporans.edit', $laporan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('laporan_delete')
                                    <form action="{{ route('admin.laporans.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

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
@can('laporan_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.laporans.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 5, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-Laporan:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection