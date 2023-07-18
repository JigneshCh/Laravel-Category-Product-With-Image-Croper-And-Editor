@extends('layouts.apex')
@section('title',trans('categories.label.create_categories'))
@section('content')

<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> @lang('categories.label.create_categories') </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/admin/categories') }}" title="@lang('common.label.back')">
                        <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> @lang('common.label.back')
                        </button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <div class="col-xl-6 col-sm-12">
                            {!! Form::open(['url' => '/admin/categories', 'class' => 'form-horizontal','autocomplete'=>'off','files' => true]) !!}
                            @include ('admin.categories.form')
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection