@extends('layouts.apex')
@section('title',trans('categories.label.show_categories'))
@section('content')
<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> @lang('categories.label.show_categories') </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="form-group">
                        <a href="{{ url('/admin/categories') }}" title="@lang('common.label.back')">
                            <button type="button" class="btn btn-raised btn-success btn-min-width mr-1 mb-1">
                                <i class="fa fa-angle-left"></i> @lang('common.label.back')
                            </button>
                        </a>
                        <a href="{{ url('/admin/categories/' . $item->id . '/edit') }}" title="@lang('tooltip.common.icon.edit')">
                            <button type="button" class="btn btn-raised btn-warning btn-min-width mr-1 mb-1">
                                <i class="fa fa-pencil"></i>
                                @lang('tooltip.common.icon.edit')
                            </button>
                        </a>
                        {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/admin/categories', $item->id],
                        'style' => 'display:inline'
                        ]) !!}
                        {!! Form::button('<i class="fa fa-trash" aria-hidden="true"></i> '.trans('tooltip.common.icon.delete'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-raised btn-danger btn-min-width mr-1 mb-1',
                        'title' => trans('common.label.delete'),
                        'onclick'=>"return confirm('".trans('common.js_msg.confirm_for_delete_data')."')"
                        ))!!}
                        {!! Form::close() !!}
                    </div>
                    <div class="next_previous pull-right">
                    </div>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <div class="box-content ">
                            <div class="row">
                                <div class="table-responsive custom-table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <td>@lang('common.label.id')</td>
                                                <td>{{ $item->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>@lang('common.label.name')</td>
                                                <td> {{ $item->name }} </td>
                                            </tr>
                                            <tr>
                                                <td>@lang('categories.label.display_order')</td>
                                                <td> {{ $item->display_order }} </td>
                                            </tr>
                                            <tr>
                                                <td>@lang('categories.label.parent_categories')</td>
                                                <td> @if($item->parent) {{ $item->parent->name }} @else - @endif </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection