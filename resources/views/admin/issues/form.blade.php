<div class="row ">
	<div class="col-md-12">
		{!! Form::hidden('status',"new", ['class' => 'form-control']) !!}
		<div class="form-group {{ $errors->has('issue_detail') ? ' has-error' : ''}}">
			<label for="issue_detail">
				Title
			</label>
			<div>
				{!! Form::text('issue_detail', old('issue_detail',null) , ['class' => 'form-control']) !!}
				{!! $errors->first('issue_detail', '<p class="help-block text-danger">:message</p>') !!}
			</div>
		</div>
		<div class="form-group {{ $errors->has('category_id') ? 'has-error' : ''}}">
			<label for="category_id" class="">
				<span class="field_compulsory">*</span>@lang('issue.label.category')
			</label>
			@if(old('category_id'))
			@php( $selected = old('category_id') )
			@elseif(isset($item))
			@php( $selected = $item->category_id )
			@else
			@php( $selected = "" )
			@endif
			<select class="form-control" id="category_id" name="category_id">
				@foreach($categories as $k => $val)
				<option value="{{$val->id}}" {{ ($val->id == $selected ) ? 'selected':'' }}>{{$val->order_label}} {{$val->name}}</option>
				@endforeach
			</select>
			{!! $errors->first('category_id', '<p class="help-block text-danger">:message</p>') !!}
		</div>
		<div class="form-group {{ $errors->has('child_category_id') ? 'has-error' : ''}}">
			<label for="child_category_id" class="">
				<span class="field_compulsory">*</span>@lang('issue.label.child_category')
			</label>
			<select class="form-control" id="child_category_id" name="child_category_id">
			</select>
			{!! $errors->first('child_category_id', '<p class="help-block text-danger">:message</p>') !!}
			<div class="card">
				<div class="card-header">
					<h4 class="card-title mb-0">@lang('categories.label.category_images')</h4>
				</div>
				<div class="card-body">
					<div class="card-block" id="cat_img_selection">
					</div>
				</div>
			</div>
		</div>
		<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
			<label for="logo"> @lang('issue.label.issue_images') </label>
			<div class="">
				@if(isset($item) && $item->refefile->count())
				<a href="{{ url('admin/items-image-edit/'.$item->id)}}"> @lang('issue.label.update_image') </a>
				@endif
				@if(isset($item) && $item->refefile->count())
				<div class="row">
					@foreach($item->refefile as $rf)
					@if($rf->file_thumb_url && $rf->file_thumb_url != "")
					<div class="col-sm-3 relative-container" id="ref{{$rf->id}}">
						<a href="{{url('admin/reference-file/'.$rf->id.'/delete')}}" onclick="return confirm('@lang('common.js_msg.confirm_for_delete',['item_name'=>trans('common.label.file')])')" class="close btn btn-danger btn-sm"><i class='fa fa-trash' aria-hidden='true'></i></a>
						<a class="example-image-link " href="{!! $rf->file_url !!}?uid={{ time() }}" data-lightbox="example-2" data-title="{{$rf->refe_file_real_name}}">
							<img class="pull-right" src="{!! $rf->file_thumb_url !!}?uid={{ time() }}" height="75" />
						</a>
						<a href="{{url('admin/reference-file/'.$rf->id.'/rotate')}}" class="rotate btn btn-danger btn-sm"><i class='ft-corner-up-right' aria-hidden='true'></i> </a>
						<a href="{{url('admin/reference-file/'.$rf->id.'/rotate')}}?dir=left" class="rotate right35 btn btn-danger btn-sm"><i class='ft-corner-up-left' aria-hidden='true'></i> </a>
					</div>
					@endif
					@endforeach
				</div>
			</div>
			<br />
			@endif
			{!! Form::file('images[]', ['id'=>'issie_images','class' => 'form-control','multiple'=>true]) !!}
			{!! $errors->first('images.*', '<p class="help-block text-danger">:message</p>') !!}
			<p> @lang('common.label.max_limit') </p>

		</div>
		<div class="form-group">
			@if(isset($item))
			{!! Form::submit(trans('common.label.save'), ['class' => 'btn btn-primary form_submit']) !!}
			@else
			{!! Form::submit(isset($submitButtonText) ? $submitButtonText : trans('common.label.create'), ['class' => 'btn btn-primary form_submit']) !!}
			@endif
			{{ Form::reset(trans('common.label.clear_form'), ['class' => 'btn btn-light']) }}
			@if(isset($item))
			@endif
		</div>
	</div>
</div>

@push('js')
<script>
	$(function() {
		var max_file_number = 30,
		// Define your form id or class or just tag.
		$form = $('form'),
		// Define your upload field class or id or tag.
		$file_upload = $('#issie_images'),
		// Define your submit class or id or tag.
		$button = $('.form_submit_', $form);
		// Disable submit button on page ready.
		$button.prop('disabled', 'disabled');

		$file_upload.on('change', function() {
			var mexsize = "";
			var number_of_images = $(this)[0].files.length;
			for (var i = 0; i <= number_of_images - 1; i++) {

				var fsize = $(this)[0].files[i].size;
				var filekb = Math.round((fsize / 1024));
				// The size of the file.
				if (filekb >= 1024 * 8) {
					mexsize = "File too Big, please select a file less than 8mb";
				}
			}
			if (number_of_images > max_file_number) {
				alert(`You can upload maximum ${max_file_number} files.`);
				$(this).val('');
				$button.prop('disabled', 'disabled');
			} else if (mexsize != "") {
				alert(mexsize);
				$(this).val('');
				$button.prop('disabled', 'disabled');
			} else {
				$button.prop('disabled', false);
			}
		});
	});

	var sub_search_url = "{{url('admin/categories/search')}}";
	var selected_cat = 0;
	var issue_id = 0;
	var default_price_info = "";

	@if(isset($item))
	issue_id = "{{ $item->id }}";
	@endif

	@if(old('child_category_id'))
	selected_cat = "{{ old('child_category_id') }}";
	@elseif(isset($item) && $item-> child_category_id && $item-> child_category_id != "" && $item-> child_category_id != 0)
	selected_cat = "{{ $item->child_category_id }}";
	@elseif(isset($item) && $item-> child_unique_id && $item-> child_unique_id != "")
	selected_cat = "{{ $item->child_unique_id }}";
	@endif

	initSelect();

	function initSelect(sid = 0) {
		if (sid && sid != 0) {
			selected_cat = sid;
		}
		$("#child_category_id").html("");
		$.ajax({
			type: "get",
			url: sub_search_url,
			data: {
				parent_id: $('#category_id').val(),
				survey_id: $('#survey_id').val()
			},
			success: function(result) {
				data = result.data;
				for (var i = 0; i < data.length; i++) {
					var selected = "";
					if (data[i]['id'] == selected_cat) {
						selected = "selected=selected";
					}
					$("#child_category_id").append("<option value='" + data[i]['id'] + "' " + selected + ">" + data[i]['order_label'] + " " + data[i]['name'] + "</option>");
				}
				catImgview();
			},
			error: function(xhr, status, error) {}
		});
	}

	function catImgview() {
		default_price_info = "";
		@if(!isset($item))
		$("#cat_img_selection").html("");
		@endif

		$.ajax({
			type: "get",
			url: "{{url('admin/categories/img-view')}}",
			data: {
				category_id: $('#child_category_id').val(),
				issue_id: issue_id
			},
			success: function(result) {
				$("#cat_img_selection").html(result.html);
			},
			error: function(xhr, status, error) {}
		});
	}
	$('#child_category_id').change(function() {
		catImgview();
	});

	$('#category_id').change(function() {
		initSelect();
	});
</script>

@endpush