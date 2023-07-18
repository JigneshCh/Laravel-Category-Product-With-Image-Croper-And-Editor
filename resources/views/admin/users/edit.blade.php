@extends('layouts.apex')
@section('title',trans('user.label.edit_user'))
@section('content')
<section id="basic-form-layouts">
	<div class="row">
            <div class="col-sm-12">
                <div class="content-header"> @lang('user.label.edit_user') # {{$user->name}} </div>
                {{-- @include('partials.page_tooltip',['model' => 'user','page'=>'form']) --}}
            </div>
        </div>
	<div class="row">
	    <div class="col-md-12">
	        <div class="card">
	            <div class="card-header">
                        <a href="{{ url('/admin/users') }}" title="Back">
                            <button class="btn btn-raised btn-success round btn-min-width mr-1 mb-1"><i class="fa fa-angle-left" aria-hidden="true"></i> @lang('common.label.back')
                            </button>
                        </a>
	            </div>
	            <div class="card-body">
	                <div class="px-3">
	                    {!! Form::model($user, [
                                'method' => 'PATCH',
                                'url' => ['/admin/users', $user->id],
                                'class' => 'form-horizontal',
                                'files' => true,
                                'autocomplete'=>'off'
                            ]) !!}
                            @include ('admin.users.form', ['submitButtonText' => trans('common.label.update')])
                            {!! Form::close() !!}
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</section>
@endsection


