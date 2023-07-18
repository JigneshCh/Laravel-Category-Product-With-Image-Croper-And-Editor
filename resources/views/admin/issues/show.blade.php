@extends('layouts.apex')
@section('title',trans('issue.label.issue_detail'))
@section('content')

<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/admin/items/'.$item->id.'/edit') }}" title="@lang('tooltip.common.icon.edit')">
                        <button type="button" class="btn btn-raised btn-warning btn-min-width mr-1 mb-1">
                            <i class="fa fa-pencil"></i>
                            @lang('tooltip.common.icon.edit')
                        </button>
                    </a>
                    {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['admin/items', $item->id],
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
                <div class="card-body">
                    <div class="px-3">
                        <div class="box-content ">
                            <div class="row">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>@lang('common.label.id')</th>
                                                <td>{{ $item->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>Title</th>
                                                <td>
                                                    <pre> {{ $item->issue_detail }} </pre>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td> {{ $item->status }}</td>
                                            </tr>
                                            <tr>
                                                <th>@lang('issue.label.category')</th>
                                                <td> @if($item->category) {{$item->category->name}} @else - @endif </td>
                                            </tr>
                                            <tr>
                                                <th>@lang('issue.label.child_category')</th>
                                                <td> @if($item->getsubcategory) {{$item->getsubcategory->name}} @else - @endif </td>
                                            </tr>
                                            <tr>
                                                <th>@lang('issue.label.issue_images')</th>
                                                <td>
                                                    @if(isset($item) && $item->refefile->count())
                                                    <a href="{{ url('admin/items-image-edit/'.$item->id)}}"> @lang('issue.label.update_image') </a>
                                                    @endif
                                                    @if($item->refefile->count())
                                                    <div class="row">
                                                        @foreach($item->refefile as $rf)
                                                        @if($rf->file_thumb_url && $rf->file_thumb_url != "")
                                                        <div class="col-sm-2 relative-container" id="ref{{$rf->id}}">
                                                            <a href="{{url('admin/reference-file/'.$rf->id.'/delete')}}" onclick="return confirm('@lang('common.js_msg.confirm_for_delete',['item_name'=>trans('common.label.file')])')" class="close btn btn-danger btn-sm"><i class='fa fa-trash' aria-hidden='true'></i></a>
                                                            <a class="example-image-link " href="{!! $rf->file_url !!}?uid={{ time() }}" data-lightbox="example-2" data-title="{{$rf->refe_file_real_name}}">
                                                                <img src="{!! $rf->file_thumb_url !!}?uid={{ time() }}" class="pull-right" height="80" />
                                                            </a>
                                                            <a href="{{url('admin/reference-file/'.$rf->id.'/rotate')}}?dir=left" class="rotate right35 btn btn-danger btn-sm"><i class='ft-corner-up-left' aria-hidden='true'></i> </a>
                                                            <a href="{{url('admin/reference-file/'.$rf->id.'/rotate')}}" class="rotate btn btn-danger btn-sm"><i class='ft-corner-up-right' aria-hidden='true'></i> </a>
                                                        </div>
                                                        @endif
                                                        @endforeach
                                                    </div>
                                                    <br />
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td> {{ $item->created_tz }} </td>
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