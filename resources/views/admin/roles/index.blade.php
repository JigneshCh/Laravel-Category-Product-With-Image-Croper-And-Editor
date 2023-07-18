@extends('layouts.apex')
@section('title','Roles')
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="content-header"> @lang('role.label.roles') </div>
    </div>
</div>
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-3">
                    </div>
                    <div class="col-9">
                    </div>
                </div>
            </div>
            <div class="card-body collapse show">
                <div class="card-block card-dashboard">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable responsive">
                            <thead>
                                <tr>
                                    <th>@lang('common.label.id')</th>
                                    <th>@lang('user.label.name')</th>
                                    <th>@lang('role.label.role')</th>
                                </tr>
                            </thead>
                       </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
@endsection

@push('js')
<script>
    var url = "{{url('admin/roles')}}";
    datatable = $('.datatable').dataTable({
        pagingType: "full_numbers",
        "language": {
            "emptyTable": "@lang('common.datatable.emptyTable')",
            "infoEmpty": "@lang('common.datatable.infoEmpty')",
            "search": "@lang('common.datatable.search')",
            "sLengthMenu": "@lang('common.datatable.show') _MENU_ @lang('common.datatable.entries')",
            "sInfo": "@lang('common.datatable.showing') _START_ @lang('common.datatable.to') _END_ @lang('common.datatable.of') _TOTAL_ @lang('common.datatable.small_entries')",
            paginate: {
                next: '@lang('common.datatable.paginate.next')',
                previous: '@lang('common.datatable.paginate.previous')',
                first: '@lang('common.datatable.paginate.first')',
                last: '@lang('common.datatable.paginate.last')',
            }
        },
        processing: true,
        serverSide: true,
        autoWidth: false,
        stateSave: false,
        order: [0, "asc"],
        columns: [{
                "data": "id",
                "name": "id",
                "searchable": false,
                "width": "8%"
            },
            {
                "data": "name",
                "name": "name",
                "width": "30%"
            },
            {
                "data": "label",
                "name": "label",
                "width": "20%"
            }
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex) {
            $('td', nRow).attr('nowrap', 'nowrap');
            return nRow;
        },
        ajax: {
            url: "{{ url('admin/roles/datatable') }}", // json datasource
            type: "get", // method , by default get
            data: function(d) {

            }
        }
    });
    $('.filter').change(function() {
        datatable.fnDraw();
    });
    $(document).on('click', '.del-item', function(e) {
        var id = $(this).attr('data-id');
        var r = confirm("@lang('common.js_msg.confirm_for_delete',['item_name'=>'Role'])");
        if (r == true) {
            $.ajax({
                type: "DELETE",
                url: url + "/" + id,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(data) {
                    datatable.fnDraw();
                    toastr.success('Action Success!', data.message)
                },
                error: function(xhr, status, error) {
                    var erro = ajaxError(xhr, status, error);
                    toastr.error('Action Not Procede!', erro)
                }
            });
        }
    });
</script>
@endpush