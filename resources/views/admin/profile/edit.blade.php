@extends('layouts.apex')
@section('title',trans('user.label.edit_profile'))
@section('content')

<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> @lang('user.label.edit_profile') </div>
            {{-- @include('partials.page_tooltip',['model' => 'profile','page'=>'form']) --}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('profile.index') }}" title="@lang('common.label._back')">
                        <button class="btn btn-success btn-sm btn-leftback"><i class="fa fa-angle-left" aria-hidden="true"></i> @lang('common.label._back') </button>
                    </a>
                </div>
                <div class="card-body">
                    <div class="px-3">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">

                                {!! Form::model($user,[
                                'method' => 'PATCH',
                                'class' => 'form-horizontal',
                                'files'=>true,
                                'autocomplete'=>'off'
                                ]) !!}

                                <div class="form-group {{ $errors->has('first_name') ? ' has-error' : ''}}">
                                    <label for="first_name" class="label-control">
                                        <span class="field_compulsory">*</span>@lang('user.label.first_name')
                                    </label>
                                    <div class="">
                                        {!! Form::text('first_name', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('first_name', '<p class="help-block text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('last_name') ? ' has-error' : ''}}">
                                    <label for="last_name" class="label-control">
                                        <span class="field_compulsory">*</span>@lang('user.label.last_name')
                                    </label>
                                    <div class="">
                                        {!! Form::text('last_name', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('last_name', '<p class="help-block text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('language') ? ' has-error' : ''}}">
                                    <label for="language" class="label-control">
                                        <span class="field_compulsory">*</span>@lang('user.label.language')
                                    </label>
                                    <div class="">
                                        {!! Form::select('language',trans('common.language') ,null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('language', '<p class="help-block text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="first_name" class="label-control"> </label>
                                    <div class="">
                                        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : trans('user.label.update_profile'), ['class' => 'btn btn-primary']) !!}
                                        {{ Form::reset(trans('common.label.clear_form'), ['class' => 'btn btn-light']) }}
                                    </div>
                                </div>
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
