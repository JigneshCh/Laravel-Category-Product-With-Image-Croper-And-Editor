@extends('layouts.apex')
@section('body_class',' pace-done')
@section('title',trans('issue.label.issues'))
@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="content-header"> @lang('issue.label.issues') </div>
    </div>
</div>
<section id="configuration">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="actions pull-left">
                            </div>
                        </div>
                        <div class="col-6">
                            @include("admin.filter_deleted")
                        </div>
                    </div>
                </div>
                <div class="card-body collapse show">
                    <div class="card-block card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable responsive">
                                <thead>
                                    <tr>
                                        <th># @lang('common.label.id')</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Created On</th>
                                        <th>@lang('common.label.action')</th>
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
    var edit_url = "{{ url('/admin/items') }}";
    var auth_check = "{{ Auth::check() }}";

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
        stateSave: true,
        order: [1, "asc"],
        columns: [{
                data: 'id',
                name: 'id',
                "searchable": true,
                "orderable": true
            },
            {
                data: 'issue_detail',
                name: 'issue_detail',
                "searchable": true,
                "orderable": true
            },
            {
                "data": null,
                "name": "status",
                "searchable": true,
                "orderable": true,
                "render": function(o) {
                    return o.status;
                }
            },
            {
                "data": null,
                "name": "created_at",
                "searchable": false,
                "orderable": true,
                "render": function(o) {
                    return o.created_a + "<br/>" + o.created_tz;
                }
            },
            {
                "data": null,
                "searchable": false,
                "orderable": false,
                "width": 150,
                "render": function(o) {
                    var e = "";
                    var v = "";
                    var d = "";
                    v = "<a href='" + edit_url + "/" + o.id + "' value=" + o.id + " data-id=" + o.id + " ><button class='btn btn-info btn-sm' title='@lang('tooltip.common.icon.eye')' ><i class='fa fa-eye' ></i></button></a>&nbsp;";

                    if (o.status != "closed") {
                        e = "<a href='" + edit_url + "/" + o.id + "/edit' value=" + o.id + " data-id=" + o.id + " title='@lang('tooltip.common.icon.edit')' class='btn btn-warning btn-sm'><i class='fa fa-pencil'></i></a>&nbsp;";
                    }
                    d = "<a href='javascript:void(0);' class='btn btn-danger btn-sm del-log' title='@lang('tooltip.common.icon.delete')' data-deleted='1' data-id=" + o.id + " ><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;";
                    if (o.deleted_at && o.deleted_at != "") {
                        return "<a href='javascript:void(0);' class='recover-item btn btn-info btn-sm' moduel='issue' data-id=" + o.id + " title='@lang('common.label.recover')'><i class='fa fa-repeat '></i></a>" + d;
                    } else {
                        return v + e + d;
                    }
                }

            }
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex) {
            $('td', nRow).attr('nowrap', 'nowrap');
            return nRow;
        },
        ajax: {
            url: "{{ url('admin/items-data') }}", // json datasource
            type: "get", // method , by default get
            data: function(d) {
                d.enable_deleted = ($('#is_deleted_record').is(":checked")) ? 1 : 0;
            }
        }
    });

    $('.filter').change(function() {
        datatable.fnDraw();
    });
    $('#is_deleted_record').change(function() {
        datatable.fnDraw();
    });

    $(document).on('click', '.recover-item', function(e) {
        var id = $(this).attr('data-id');
        var moduel = $(this).attr('moduel');
        var r = confirm("@lang('common.js_msg.confirm_for_delete_recover',['item_name'=>'Survey'])");
        if (r == true) {
            $.ajax({
                type: "POST",
                url: "{{ url('admin/recover-item') }}",
                data: {
                    item: moduel,
                    id: id
                },
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

    $(document).on('click', '.del-log', function(e) {
        var id = $(this).attr('data-id');
        var r = confirm("@lang('common.js_msg.confirm_for_delete_data')");
        if (r == true) {
            $.ajax({
                type: "DELETE",
                url: "{{ url('/admin/items') }}" + "/" + id,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(data) {
                    datatable.fnDraw();
                    toastr.success("@lang('common.js_msg.action_success')", data.message)
                },
                error: function(xhr, status, error) {
                    toastr.error("@lang('common.js_msg.action_not_procede')", erro)
                }
            });
        }
    });
</script>
@endpush