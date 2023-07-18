<div class="form-group  {{ $errors->has('parent_id') ? ' has-error' : ''}}">
	<label for="parent_id">@lang('categories.label.parent_categories')
	</label>
	<div class="">
		{!! Form::select('parent_id',$items, null, ['class' => 'form-control filter','id'=>'parent_id']) !!}
		{!! $errors->first('parent_id', '<p class="help-block text-danger">:message</p>') !!}
	</div>
</div>
<div class="form-group {{ $errors->has('display_order') ? ' has-error' : ''}}">
	<label for="display_order">
		<span class="field_compulsory">*</span>
		@lang('categories.label.display_order')
	</label>
	<div>
		{!! Form::number('display_order', null, ['class' => 'form-control']) !!}
		{!! $errors->first('display_order', '<p class="help-block text-danger">:message</p>') !!}
	</div>
</div>
<div class="form-group  {{ $errors->has('name') ? ' has-error' : ''}}">
	<label for="name">
		<span class="field_compulsory">*</span>
		@lang('common.label.name')
	</label>
	<div class="">
		{!! Form::text('name', null, ['class' => 'form-control']) !!}
		{!! $errors->first('name', '<p class="help-block text-danger">:message</p>') !!}
	</div>
</div>
<div class="prefilled_inputs">
	<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
		<label for="logo"> Image </label>
		<div class="">
			@if(isset($item) && $item->refefile->count())
			@php( $img_quote = [] )
			@if($item->quote_desc && $item->quote_desc != "")
			@php( $img_quote = json_decode($item->quote_desc,true) )
			@endif
			@foreach($item->refefile as $rf)
			@if($rf->file_thumb_url && $rf->file_thumb_url != "")
			<div class="row">
				<div class="col-sm-6 relative-container">
					<a href="{{url('admin/reference-file/'.$rf->id.'/delete')}}" onclick="return confirm('@lang('common.js_msg.confirm_for_delete',['item_name'=>trans('common.label.file')])')" class="close btn btn-danger btn-sm"><i class='fa fa-trash' aria-hidden='true'></i></a>
					<a class="example-image-link " href="{!! $rf->file_url !!}" data-lightbox="example-2" data-title="{{$rf->refe_file_real_name}}">
						<img src="{!! $rf->file_thumb_url !!}" class="pull-right" height="80" />
					</a>
				</div>
				<div class="col-sm-6">
					{!! Form::textarea('quote_desc['.$rf->id.']', isset($img_quote[$rf->id])? $img_quote[$rf->id] : null , ['rows'=>'2','placeholder'=>"Image detail",'class' => 'form-control','style'=>'margin-top:20px']) !!}
					{!! $errors->first('quote_desc', '<p class="help-block text-danger">:message</p>') !!}
				</div>
			</div>
			@endif
			@endforeach
			<br />
			@endif
			{!! Form::file('images[]', ['class' => 'form-control','multiple'=>true]) !!}
			{!! $errors->first('images.*', '<p class="help-block text-danger">:message</p>') !!}
		</div>
	</div>
</div>
<div class="form-group">
	{!! Form::submit(isset($submitButtonText) ? $submitButtonText : trans('common.label.create'), ['class' => 'btn btn-primary']) !!}
	{{ Form::reset(trans('common.label.clear_form'), ['class' => 'btn btn-light']) }}
</div>

@push('js')
<script>
	$('.filter').change(function() {
		togleinput();
	});
	togleinput();
	function togleinput() {
		if ($("#parent_id").val() <= 0) {
			$(".prefilled_inputs").hide();
		} else {
			$(".prefilled_inputs").show();
		}
	}
</script>
@endpush