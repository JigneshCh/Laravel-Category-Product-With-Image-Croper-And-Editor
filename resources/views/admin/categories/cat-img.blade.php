@if(isset($cat) && $cat->refefile->count() >0 )
@foreach($cat->refefile as $k => $ref)
<div class="row">
	<div class="col-sm-2 relative-container">
		<div class="media mb-1 pull-right">
			<div class="mt-1">
				<div class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
					<input type="checkbox" name="cat_img[{{$ref->id}}]" @if(in_array($ref->id,$selected_img) || !$issue) checked @endif class="custom-control-input" value="1" id="cat_img{{$k}}">
					<label class="custom-control-label" for="cat_img{{$k}}"></label>
				</div>
			</div>
			<a class="example-image-link " href="{!! $ref->file_url !!}" data-lightbox="example-2" data-title="">
				<img class="height-50" style="margin-right: 0rem !important;" src="{{$ref->file_thumb_url}}">
			</a>
		</div>
	</div>
	<div class="col-sm-10">
		<?php
		$img_quote = [];
		if ($cat->quote_desc && $cat->quote_desc != "") {
			$img_quote = json_decode($cat->quote_desc, true);
		}
		$_quote_text = null;
		if (isset($img_hints[$ref->id])) {
			$_quote_text = $img_hints[$ref->id];
		} else if (isset($img_quote[$ref->id])) {
			$_quote_text = $img_quote[$ref->id];
		}
		?>
		{!! Form::textarea('quote_desc['.$ref->id.']', $_quote_text , ['rows'=>'2','placeholder'=>'Image Detail','class' => 'form-control','style'=>'margin-top:20px']) !!}
	</div>
</div>
@endforeach
@endif