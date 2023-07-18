@extends('layouts.apex')
@section('title',trans('categories.label.edit_categories'))
@section('content')

<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> @lang('categories.label.edit_categories')</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/admin/categories') }}" title="@lang('common.label.back')">
                        <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> @lang('common.label.back') </button>
                    </a>
                    @if(!$item->next())
                    <a href="#" title="@lang('common.label.update')" onclick="event.preventDefault(); document.getElementById('cat_form').submit();">
                        <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"> @lang('common.label.update') </button>
                    </a>
                    @else
                    <a href="#" title="@lang('common.label.saveandnext')" onclick="event.preventDefault(); document.getElementById('cat_form').submit();">
                        <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"> @lang('common.label.saveandnext') </button>
                    </a>
                    @endif


                    @if($item->next())
                    <a href="{{ url('admin/categories/'.$item->next()->id.'/edit') }}" title="@lang('common.label.next')">
                        <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"> @lang('common.label.next') </button>
                    </a>
                    @endif

                    @if($item->previous())
                    <a href="{{ url('admin/categories/'.$item->previous()->id.'/edit') }}" title="@lang('common.label.previous')">
                        <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"> @lang('common.label.previous') </button>
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                {!! Form::model($item, [
                                'method' => 'PATCH',
                                'url' => ['/admin/categories', $item->id],
                                'class' => 'form-horizontal',
                                'files' => true,
                                'autocomplete'=>'off',
                                'id'=>'cat_form'
                                ]) !!}

                                @include ('admin.categories.form',['submitButtonText'=> $item->next() ? trans('common.label.saveandnext') : trans('common.label.update')])

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection