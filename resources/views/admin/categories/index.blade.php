@extends('layouts.apex')
@section('body_class',' pace-done')
@section('title',trans('categories.label.categories'))
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="content-header"> @lang('categories.label.categories') </div>
    </div>
</div>
<section id="configuration" class="categories-section">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-3">
                            <div class="actions pull-left">
                                <a href="{{ url('/admin/categories/create') }}" class="btn btn-success btn-sm" title="@lang('common.label.add_new')">
                                    <i class="fa fa-plus" aria-hidden="true"></i> @lang('common.label.add_new')
                                </a>
                            </div>
                        </div>
                        <div class="col-9">
                            <div class="actions">
                                <form method="get" action="{{url('admin/categories/export')}}">
                                    <div class="form-group row">
                                        <label for="name" class="col-xl-8 col-sm-8 label-control">
                                            <select class="form-control" id="category_id" name="category_id">
                                                <option value="">@lang('common.label.all')</option>
                                                @foreach($items as $k => $val)
                                                <option value="{{$val->id}}">{{$val->order_label}} {{$val->name}}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                        {!! Form::hidden('print',1, ['class' => 'form-control',"id"=>""]) !!}
                                        <div class="col-xl-4 col-sm-4">
                                            {!! Form::submit('View', ['class' => 'btn btn-primary']) !!}
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row match-height">
        <div class="col-lg-12 col-xl-12">
            <div id="accordionWrap1" role="tablist" aria-multiselectable="true">
                <div class="card collapse-icon accordion-icon-rotate">
                    @php( $cat_count = 0 )
                    @foreach($items as $item)
                    <div id="heading{{$item->id}}" class="card-header">
                        <a href="{{ url('/admin/categories/' . $item->id) }}" title="@lang('tooltip.common.icon.eye')">
                            <span class="text-warning" id="category_{{$item->id}}">{{$item->display_order}} </span>
                        </a>
                        <a data-toggle="collapse" data-parent="#accordionWrap1" href="#accordion{{$item->id}}" aria-expanded="false" aria-controls="accordion{{$item->id}}" class="card-title lead collapsed"> {{$item->name}}</a>
                    </div>
                    <div id="accordion{{$item->id}}" role="tabpanel" aria-labelledby="heading{{$item->id}}" class="collapse" aria-expanded="false">
                        <div class="card-body">
                            <div class="card-block">
                                <table class="table table-bordered">
                                    <th colspan="3"><span class="font-600">@lang('categories.label.child_categories')</span></th>
                                    @php( $cat_count = $cat_count + $item->child->count() + 1 )
                                    @if($item->child)
                                    @foreach($item->child as $sub)
                                    @if($sub->is_hidden !=1)
                                    <tr>
                                        <td class="width-65" id="category_{{$sub->id}}">
                                            <i class="text-success"><span class="text-warning">{{$item->display_order}}.{{$sub->display_order}} </span> {{$sub->name}}</i>
                                        </td>
                                        <td class="width-35">
                                            <a href="{{ url('/admin/categories/' . $sub->id) }}" class="btn btn-info btn-xs ipad-mb10" title="@lang('tooltip.common.icon.eye')">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ url('/admin/categories/' . $sub->id . '/edit') }}" class="btn btn-primary btn-xs ipad-mb10" title="@lang('tooltip.common.icon.edit')">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            @if( 1 )
                                            {!! Form::open([
                                            'method' => 'DELETE',
                                            'url' => ['/admin/categories', $sub->id],
                                            'style' => 'display:inline'
                                            ]) !!}
                                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ', array(
                                            'type' => 'submit',
                                            'class' => 'btn btn-danger btn-xs',
                                            'title' => trans('tooltip.common.icon.delete'),
                                            'onclick'=>"return confirm('".trans('common.js_msg.confirm_for_delete_data')."')"
                                            )) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endsection