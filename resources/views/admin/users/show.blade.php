@extends('layouts.apex')
@section('title',trans('user.label.show_user'))
@section('content')

<section id="basic-form-layouts">
    <div class="row">
        <div class="col-sm-12">
            <div class="content-header"> @lang('user.label.show_user')</div>
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
                                                <td>{{ $user->id }}</td>
                                            </tr>
                                            <tr>
                                                <td>@lang('user.label.first_name')</td>
                                                <td> {{ $user->first_name }} </td>
                                            </tr>
                                            <tr>
                                                <td>@lang('user.label.last_name')</td>
                                                <td> {{ $user->last_name }} </td>
                                            </tr>
                                            <tr>
                                                <td>@lang('user.label.email')</td>
                                                <td> {{ $user->email }} </td>
                                            </tr>
                                            <tr>
                                                <td>@lang('common.label.status')</td>
                                                <td> {{ $user->status }} </td>
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