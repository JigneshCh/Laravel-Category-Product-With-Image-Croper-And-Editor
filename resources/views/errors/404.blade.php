@extends('layouts.apex-auth')

@section('title',trans('common.responce_msg.page_not_found') )

@section('content')

@push('css')
<style>
.main-panel{
	margin-top: 0px !important;
}
</style>
@endpush
<section id="error">
    <div class="container-fluid bg-grey bg-lighten-3">
        <div class="container">
            <div class="row full-height-vh">
                <div class="col-md-12 col-lg-3 ml-auto d-flex align-items-center">
                    <div class="row text-center mb-3">
                        <div class="col-12">
                            <img src="{{ asset('public/assets/images/logo.png') }}" alt="User" width="300">
                        </div>
                        <div class="col-12">
                            <h4 class="grey darken-2 font-large-5">@lang('common.label.Opps')</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-8 d-flex align-items-center justify-content-center">
                    <div class="error-container">
                        <div class="no-border">
                            <div class="text-center text-bold-700 grey darken-2 mt-5" style="font-size: 11rem; margin-bottom: 4rem;">404</div>
                        </div>
                        <div class="error-body">
                           
                            <div class="row py-2 justify-content-center">
								<b>@lang('common.responce_msg.page_not_found') </b>
                                <div class="col-8">
                                    <a href="{{url('/')}}" class="btn btn-brown btn-raised btn-block font-medium-2"><i class="ft-home"></i>  @lang('common.label.go_home')</a>
                                </div>
                            </div>
                        </div>
                        <div class="error-footer bg-transparent">
                            <div class="row">
                                <p class="text-muted text-center col-12 py-1"> <?php $today = getdate(); ?>
        &copy; <a href="#" target="_blank"> {{ config('app.name') }} {{$today['year']}} </a>@lang('common.label.all_rights')</p>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
