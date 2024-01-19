@extends('layouts.admin')
@section('content')
@can('tugar_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.tugars.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.tugar.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.tugar.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Tugar">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.petugas') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.pengaduan') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.kategori') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.judul_tugas') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.keterangan') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.status') }}
                        </th>
                        <th>
                            {{ trans('cruds.tugar.fields.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tugars as $key => $tugar)
                        <tr data-entry-id="{{ $tugar->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $tugar->petugas->name ?? '' }}
                            </td>
                            <td>
                                {{ $tugar->pengaduan->nama_lengkap ?? '' }}
                            </td>
                            <td>
                                {{ $tugar->kategori->nama_kategori ?? '' }}
                            </td>
                            <td>
                                {{ $tugar->judul_tugas ?? '' }}
                            </td>
                            <td>
                                {{ $tugar->keterangan ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\Tugar::STATUS_RADIO[$tugar->status] ?? '' }}
                            </td>
                            <td>
                                {{ $tugar->created_at ?? '' }}
                            </td>
                            <td>
                                @can('tugar_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.tugars.show', $tugar->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('tugar_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.tugars.edit', $tugar->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('tugar_delete')
                                    <form action="{{ route('admin.tugars.destroy', $tugar->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('tugar_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tugars.massDestroy') }}",
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
    order: [[ 7, 'desc' ]],
    pageLength: 10,
  });
  let table = $('.datatable-Tugar:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection