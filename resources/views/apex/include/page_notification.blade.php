@push('js')


<script>
var option = {"positionClass": "toast-top-right","timeOut": "10000"};    
$(document).ready(function(){ 
    @if (Session::has('flash_message'))
        toastr.info("@lang('common.js_msg.action_success')","{{ Session::get('flash_message') }}",option);
    @endif

    @if (Session::has('flash_warning'))
        toastr.warning("@lang('common.js_msg.action_not_procede')","{{ Session::get('flash_warning') }}",option);
    @endif

    @if (Session::has('flash_error'))
        toastr.error("@lang('common.js_msg.action_not_procede')","{{ Session::get('flash_error') }}",option);
    @endif

    @if (Session::has('flash_success'))
        toastr.success("@lang('common.js_msg.action_success')","{{ Session::get('flash_success') }}",option);
    @endif
	@if (Session::has('hashtag'))
		var targetOffset = $("#{{Session::get('hashtag')}}").offset().top + 100;
		$('html, body').animate({scrollTop: targetOffset}, 1000);
		<?php Session::forget('hashtag'); ?>
	@endif
});
    
</script>
@endpush