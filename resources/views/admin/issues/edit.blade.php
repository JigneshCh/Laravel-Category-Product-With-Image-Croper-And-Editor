@extends('layouts.apex')
@section('title',trans('issue.label.issues'))
@section('content')
<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> @lang('issue.label.issues') </div>
            {{-- @include('partials.page_tooltip',['model' => 'user','page'=>'form']) --}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['admin/issues', $item->id],
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
                        {!! Form::model($item, [
                        'method' => 'PATCH',
                        'url' => ['/admin/items', $item->id],
                        'class' => 'form-horizontal',
                        'files' => true,
                        'autocomplete'=>'off'
                        ]) !!}
                        @include ('admin.issues.form', ['submitButtonText' => trans('common.label.update')])
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection