@extends('layouts.admin')
@section('content')

<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a class="btn btn-success" href="{{ route('admin.pengguna.create') }}">
            Tambah Pengguna
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        Daftar Pengguna
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Pengguna">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            Nama
                        </th>
                        <th>
                            Email
                        </th>
                        <th>
                            NIP
                        </th>
                        <th>
                            Golongan
                        </th>
                        <th>
                            Jenis Kelamin
                        <th>
                            No. Telp
                        </th>
                        <th>
                            Foto
                        </th>
                        <th>
                            Role
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                    <tr data-entry-id="{{ $user->id }}">
                        <td>

                        </td>
                        <td>
                            {{ $user->name ?? '' }}
                        </td>
                        <td>
                            {{ $user->email ?? '' }}
                        </td>
                        <td>
                            {{ $user->nip ?? '' }}
                        </td>
                        <td>
                            {{ $user->golongan ?? '' }}
                        </td>
                        <td>
                            {{ App\Models\User::JENIS_KELAMIN_RADIO[$user->jenis_kelamin] ?? '' }}
                        </td>
                        <td>
                            {{ $user->no_telp ?? '' }}
                        </td>
                        <td>
                            @if($user->avatar)
                            <a href="{{ $user->avatar->getUrl() }}" target="_blank" style="display: inline-block">
                                <img src="{{ $user->avatar->getUrl('thumb') }}">
                            </a>
                            @endif
                        </td>
                        <td>
                            {{ $user->role == 1 ? 'Kepala Petugas' : 'Petugas Lapangan' }}
                        </td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.pengguna.show', $user->id) }}">
                                {{ trans('global.view') }}
                            </a>
                            <a class="btn btn-xs btn-info" href="{{ route('admin.pengguna.edit', $user->id) }}">
                                {{ trans('global.edit') }}
                            </a>
                            <form action="{{ route('admin.pengguna.destroy', $user->id) }}" method="POST"
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
            url: "{{ route('admin.pengguna.massDestroy') }}",
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
                [1, 'desc']
            ],
            pageLength: 10,
        });
        let table = $('.datatable-Pengguna:not(.ajaxTable)').DataTable({
            buttons: dtButtons
        })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection
