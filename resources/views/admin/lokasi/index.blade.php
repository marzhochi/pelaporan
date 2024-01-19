@extends('layouts.admin')
@section('content')
@can('lokasi_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.lokasi.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.lokasi.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.lokasi.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Lokasi">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.lokasi.fields.nama_lokasi') }}
                        </th>
                        <th>
                            {{ trans('cruds.lokasi.fields.kelurahan') }}
                        </th>
                        <th>
                            {{ trans('cruds.lokasi.fields.kecamatan') }}
                        </th>
                        <th>
                            {{ trans('cruds.lokasi.fields.kota') }}
                        </th>
                        <th>
                            {{ trans('cruds.lokasi.fields.provinsi') }}
                        </th>
                        <th>
                            {{ trans('cruds.lokasi.fields.kodepos') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lokasi as $key => $item)
                        <tr data-entry-id="{{ $item->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $item->nama_lokasi ?? '' }}
                            </td>
                            <td>
                                {{ $item->kelurahan ?? '' }}
                            </td>
                            <td>
                                {{ $item->kecamatan ?? '' }}
                            </td>
                            <td>
                                {{ $item->kota ?? '' }}
                            </td>
                            <td>
                                {{ $item->provinsi ?? '' }}
                            </td>
                            <td>
                                {{ $item->kodepos ?? '' }}
                            </td>
                            <td>
                                @can('lokasi_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.lokasi.show', $item->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('lokasi_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.lokasi.edit', $item->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('lokasi_delete')
                                    <form action="{{ route('admin.lokasi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('lokasi_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.lokasi.massDestroy') }}",
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
    order: [[ 1, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-Lokasi:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
