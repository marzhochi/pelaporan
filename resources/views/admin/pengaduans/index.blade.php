@extends('layouts.admin')
@section('content')
@can('pengaduan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.pengaduans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.pengaduan.title_singular') }}
            </a>
        </div>
    </div>
@endcan
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
                    @foreach($pengaduans as $key => $pengaduan)
                        <tr data-entry-id="{{ $pengaduan->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $pengaduan->nama_lengkap ?? '' }}
                            </td>
                            <td>
                                {{ $pengaduan->no_telepon ?? '' }}
                            </td>
                            <td>
                                {{ $pengaduan->judul_pengaduan ?? '' }}
                            </td>
                            <td>
                                {{ $pengaduan->keterangan ?? '' }}
                            </td>
                            <td>
                                @foreach($pengaduan->foto as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                        <img src="{{ $media->getUrl('thumb') }}">
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                {{ $pengaduan->latlang ?? '' }}
                            </td>
                            <td>
                                {{ $pengaduan->lokasi->nama_lokasi ?? '' }}
                            </td>
                            <td>
                                {{ $pengaduan->created_at ?? '' }}
                            </td>
                            <td>
                                @can('pengaduan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.pengaduans.show', $pengaduan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('pengaduan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.pengaduans.edit', $pengaduan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('pengaduan_delete')
                                    <form action="{{ route('admin.pengaduans.destroy', $pengaduan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('pengaduan_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pengaduans.massDestroy') }}",
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
    order: [[ 8, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-Pengaduan:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection