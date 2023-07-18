@extends('layouts.apex')
@section('title',trans('user.label.change_password'))
@section('content')

<section id="basic-form-layouts">
	<div class="row">
		<div class="col-sm-12">
			<div class="content-header"> @lang('user.label.change_password') </div>
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
								<div class="form-group{{ $errors->has('current_password') ? ' has-error' : ''}}">
									{!! Form::label('current_password', trans('user.label.current_password'), ['class' => 'label-control']) !!}
									<div class="">
										{!! Form::password('current_password', ['class' => 'form-control']) !!}
										{!! $errors->first('current_password', '<p class="help-block text-danger">:message</p>') !!}
									</div>
								</div>
								<div class="form-group {{ $errors->has('password') ? ' has-error' : ''}}">
									{!! Form::label('password', trans('common.label.password'), ['class' => 'label-control']) !!}
									<div class="">
										{!! Form::password('password', ['class' => 'form-control ']) !!}
										{!! $errors->first('password', '<p class="help-block text-danger">:message</p>') !!}
									</div>
								</div>
								<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : ''}}">
									{!! Form::label('password_confirmation', trans('user.label.password_confirmation'), ['class' => 'label-control']) !!}
									<div class="">
										{!! Form::password('password_confirmation', ['class' => 'form-control ']) !!}
										{!! $errors->first('password_confirmation', '<p class="help-block text-danger">:message</p>') !!}
									</div>
								</div>
								<div class="form-group">
									<label for="first_name" class="label-control">
									</label>
									<div class="">
										{!! Form::submit(isset($submitButtonText) ? $submitButtonText : trans('user.label.change_password'), ['class' => 'btn btn-primary']) !!}
										{{ Form::reset(trans('common.label.clear_form'), ['class' => 'btn btn-light']) }}
									</div>
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