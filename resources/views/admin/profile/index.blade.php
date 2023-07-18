@extends('layouts.apex')
@section('title',trans('user.label.my_profile'))
@section('content')

<section id="basic-form-layouts">
	<div class="row">
		<div class="col-sm-12">
			<div class="content-header"> @lang('user.label.my_profile') </div>
			{{-- @include('partials.page_tooltip',['model' => 'website','page'=>'index']) --}}
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<a href="{{ route('profile.edit') }}" class="btn btn-success btn-sm btn-custom" title="Edit Profile">
						<i class="fa fa-pencil" aria-hidden="true"></i> @lang('user.label.edit_profile')
					</a>
				</div>
				<div class="card-body">
					<div class="px-3">
						<div class="box-content ">
							<div class="row">
								<div class="table-responsive custom-table-responsive">
									<table class="table table-striped">
										<tbody>
											<tr>
												<th>@lang('user.label.first_name')</th>
												<td>{{$user->first_name}}</td>
											</tr>
											<tr>
												<th>@lang('user.label.last_name')</th>
												<td>{{$user->last_name}}</td>
											</tr>
											<tr>
												<th>@lang('common.label.email')</th>
												<td>{{$user->email}}</td>
											</tr>
											<tr>
												<th>@lang('user.label.language')</th>
												<td>@lang('common.language.'.$user->language) </td>
											</tr>
											<tr>
												<th>@lang('user.label.joined')</th>
												<td>{{$user->created_at->diffForHumans()}}</td>
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