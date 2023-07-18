@extends('layouts.apex')
@section('title',trans('issue.label.issue_image_update'))
@section('content')

<?php $height = 500;
$width = 850;
$adj_hight = 0; ?>

@if(Request::has('width') && Request::get('width') >0 )
@php( $width=Request::get('width') )
@endif

@if(isset($ifile) && $ifile)
<?php
$size = getimagesize($ifile->file_path);
if ($size && isset($size[0]) && isset($size[1])) {
	$adj_hight = (int) (($size[1] * $width) / $size[0]);
}
?>
@endif
@if(Request::has('height') && Request::get('height') >0 )
@php( $height=Request::get('height') )
@elseif($adj_hight)
@php( $height=$adj_hight )
@endif
<section id="basic-form-layouts">
	<div class="row">
		<div class="col-sm-12">
			<div class="content-header"> @lang('issue.label.issue_image_update') </div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card">
				<div class="card-header">
					<a href="{{ url('/admin/items/'.$item->id) }}" class="price_option" title="@lang('issue.label.issue_detail')">
						<button type="button" class="btn btn-raised btn-secondary btn-min-width mr-1 mb-1">
							<i class="fa fa-eye"></i> @lang('issue.label.issue_detail')
						</button>
					</a>
				</div>
				<div class="card-body">
					<div class="px-3">
						<div class="box-content ">
							<div class="row">
								<div class="col-md-10" style="width:850px">
									<form action="{{url('admin/items-image-update')}}" enctype="multipart/form-data" class="form-horizontal" id="form_create_video" onsubmit="return captureImage(3)" method="post">
										@csrf
										<input type="hidden" name="base_64_img" id="base_64_img" value="">
										<input type="hidden" name="file_id" value="{{ Request::get('selected_file') }}">
										<input type="hidden" name="issue_id" value="{{ $item->id }}">
										<div id="cnt" style="width:{{$width}}px">
											<div id="cnt" style="width:{{$width+5}}px !important;border: 1px solid #4545AC;">
												<canvas id="canvas" width="{{$width}}" height="{{$height}}" style="width:100% !important"></canvas>
											</div>
										</div>
										<div class="form-group" style="width:{{$width}}px">
											<input type="submit" class="btn btn-success" value="@lang('issue.label.update_image')" />
											<a href="{{ request()->fullUrlWithQuery([]) }}" class="btn btn-success" style="color:#FFF" />@lang('common.label.clear_form')</a>
										</div>
									</form>
									<input type="hidden" name="disable_arrow" id="disable_arrow" value="0">
								</div>
								<div class="col-md-2">
									<div class="card-body">
										<div class="form-group">
											<input type="button" class="form-control btn btn-primary" onclick="return addCircle()" value="@lang('issue.label.add_circle')" />
										</div>
										<div class="form-group">
											<input type="button" class="form-control btn btn-primary" onclick="return addArrow()" value="@lang('issue.label.add_arrow')" />
										</div>
										<div class="form-group">
											<input type="text" name="new_text" class="form-control" id="new_text" value="@lang('issue.label.enter_text')" />
											<input type="button" class="form-control btn btn-primary" onclick="return addText()" value="@lang('issue.label.add_text')" id="add_text" />
										</div>
										<div class="form-group">
											<input type="text" name="new_text" class="form-control" id="new_time" value='{{ \Carbon\Carbon::now()->format("d-m-Y H:i:s") }}' />
											<input type="button" class="form-control btn btn-primary" onclick="return addTimeStamp()" value="@lang('issue.label.add_time')" id="add_time" />
										</div>
										<div class="form-group">
											<form action="" enctype="multipart/form-data" id="">
												<input type="hidden" name="selected_file" value="{{ Request::get('selected_file') }}">
												<input type="number" name="width" class="form-control" placeholder="@lang('issue.label.image_width')" id="width" value="{{$width}}" />
												<input type="number" name="height" class="form-control" placeholder="@lang('issue.label.image_height')" id="height" value="{{$height}}" />
												<input type="submit" class="form-control btn btn-primary" value="@lang('issue.label.change_dimension')" id="change_dimension" />
											</form>
										</div>
									</div>
								</div>
							</div>
							@if(isset($ifile) && $ifile)
							<div style="display:none">
								<img src="{{$ifile->file_url}}" id="cap_img" class="capture_img_list" height="75">
							</div>
							<div class="row">
								<div class="col-md-10">
								</div>
							</div>
							@endif
							<div class="row">
								<div class="table-responsive">
									<table class="table table-striped">
										<tbody>
											<tr>
												<th>@lang('issue.label.select_image_to_update')</th>
												<td>
													@if($item->refefile->count())
													<div class="row">
														@foreach($item->refefile as $rf)
														@if($rf->file_thumb_url && $rf->file_thumb_url != "")
														<div class="col-sm-2 relative-container" id="ref{{$rf->id}}">
															<a href="{{url('admin/reference-file/'.$rf->id.'/delete')}}" onclick="return confirm('@lang('common.js_msg.confirm_for_delete',['item_name'=>trans('common.label.file')])')" class="close btn btn-danger btn-sm"><i class='fa fa-trash' aria-hidden='true'></i></a>
															<a class="example-image-link " href="{!! $rf->file_url !!}?uid={{ time() }}" data-lightbox="example-2" data-title="{{$rf->refe_file_real_name}}">
																<img src="{!! $rf->file_thumb_url !!}?uid={{ time() }}" class="pull-right" height="80" />
															</a>
															<a href="{{url('admin/items-image-edit/'.$item->id)}}?selected_file={{$rf->id}}" class="btn btn-success btn-sm rotate"><i class="fa fa-check" aria-hidden="true"></i> </a>
														</div>
														@endif
														@endforeach
													</div>
													<br />
													@endif
												</td>
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
@push('js')
@if(isset($ifile) && $ifile)

<script>
	var myVar;
	fabric.LineArrow = fabric.util.createClass(fabric.Line, {
		type: 'lineArrow',
		initialize: function(element, options) {
			options || (options = {});
			this.callSuper('initialize', element, options);
		},

		toObject: function() {
			return fabric.util.object.extend(this.callSuper('toObject'));
		},

		_render: function(ctx) {
			this.ctx = ctx;
			this.callSuper('_render', ctx);
			let p = this.calcLinePoints();
			let xDiff = this.x2 - this.x1;
			let yDiff = this.y2 - this.y1;
			let angle = Math.atan2(yDiff, xDiff);
			this.drawArrow(angle, p.x2, p.y2, this.heads[0]);
			ctx.save();
			xDiff = -this.x2 + this.x1;
			yDiff = -this.y2 + this.y1;
			angle = Math.atan2(yDiff, xDiff);
			this.drawArrow(angle, p.x1, p.y1, this.heads[1]);
		},

		drawArrow: function(angle, xPos, yPos, head) {
			this.ctx.save();

			if (head) {
				this.ctx.translate(xPos, yPos);
				this.ctx.rotate(angle);
				this.ctx.beginPath();
				// Move 5px in front of line to start the arrow so it does not have the square line end showing in front (0,0)
				this.ctx.moveTo(10, 0);
				this.ctx.lineTo(-25, 25);
				this.ctx.lineTo(-25, -25);
				this.ctx.closePath();
			}
			this.ctx.fillStyle = this.stroke;
			this.ctx.fill();
			this.ctx.restore();
		}
	});
	fabric.LineArrow.fromObject = function(object, callback) {
		callback && callback(new fabric.LineArrow([object.x1, object.y1, object.x2, object.y2], object));
	};

	fabric.LineArrow.async = true;
	var Arrow = (function() {
		function Arrow(canvas) {
			this.canvas = canvas;
			this.className = 'Arrow';
			this.isDrawing = false;
			this.bindEvents();
		}
		Arrow.prototype.bindEvents = function() {
			var inst = this;
			inst.canvas.on('mouse:down', function(o) {
				inst.onMouseDown(o);
			});
			inst.canvas.on('mouse:move', function(o) {
				inst.onMouseMove(o);
			});
			inst.canvas.on('mouse:up', function(o) {
				inst.onMouseUp(o);
				$("#disable_arrow").val(1);
			});
			inst.canvas.on('object:moving', function(o) {
				inst.disable();
			})
		}

		Arrow.prototype.onMouseUp = function(o) {
			if ($("#disable_arrow").val() == 1 || $("#disable_arrow").val() == "1") {
				return;
			}
			var inst = this;
			this.line.set({
				dirty: true,
				objectCaching: true
			});
			inst.canvas.renderAll();
			inst.disable();
		};

		Arrow.prototype.onMouseMove = function(o) {
			if ($("#disable_arrow").val() == 1 || $("#disable_arrow").val() == "1") {
				return;
			}

			var inst = this;
			if (!inst.isEnable()) {
				return;
			}

			var pointer = inst.canvas.getPointer(o.e);
			var activeObj = inst.canvas.getActiveObject();
			activeObj.set({
				x2: pointer.x,
				y2: pointer.y
			});
			activeObj.setCoords();
			inst.canvas.renderAll();
		};

		Arrow.prototype.onMouseDown = function(o) {
			if ($("#disable_arrow").val() == 1 || $("#disable_arrow").val() == "1") {
				return;
			}
			var inst = this;
			inst.enable();
			var pointer = inst.canvas.getPointer(o.e);

			var points = [pointer.x, pointer.y, pointer.x, pointer.y];
			this.line = new fabric.LineArrow(points, {
				strokeWidth: 6,
				fill: 'red',
				stroke: 'red',
				originX: 'center',
				originY: 'center',
				hasBorders: false,
				hasControls: false,
				objectCaching: false,
				perPixelTargetFind: true,
				heads: [1, 0]
			});

			inst.canvas.add(this.line).setActiveObject(this.line);
		};

		Arrow.prototype.isEnable = function() {
			return this.isDrawing;
		}

		Arrow.prototype.enable = function() {
			this.isDrawing = true;
		}

		Arrow.prototype.disable = function() {

			this.isDrawing = false;
		}

		return Arrow;
	}());

	var canvas = new fabric.Canvas('canvas', {
		selection: false
	});
	canvas.backgroundColor = 'white';
	var arrow = new Arrow(canvas);

	function addText(ishide = 0) {
		$("#disable_arrow").val(1);
		var new_text = $("#new_text").val();
		var left = 50;
		var width = 150;
		if (ishide == 1) {
			left = -350;
			width = 1;
			new_text = "";
		}
		var textbox = new fabric.Textbox(new_text, {
			left: left,
			top: 50,
			width: width,
			fontSize: 20
		});

		canvas.add(textbox).setActiveObject(textbox);
	}

	function addTimeStamp() {
		$("#disable_arrow").val(1);

		var new_time = $("#new_time").val();
		var textbox = new fabric.Textbox(new_time, {
			right: 150,
			bottom: 150,
			width: 150,
			fontSize: 20,
			fill: '#D81B60'
		});

		canvas.add(textbox).setActiveObject(textbox);
	}

	setTimeout(function() {
		addImage("cap_img");
	}, 3000);

	var imgInstance;

	function addImage(img_id) {
		$("#disable_arrow").val(1);

		var imgElement = document.getElementById(img_id);

		imgInstance = new fabric.Image(imgElement, {
			left: 0,
			top: 0,
			angle: 0
		});
		imgInstance.scaleToWidth({{ $width }});
		canvas.add(imgInstance).setActiveObject(imgInstance);
	}
	function addCircle() {
		$("#disable_arrow").val(1);
		var circle2 = new fabric.Circle({
			radius: 40,
			left: 110,
			fill: 'rgba(0,0,0,0)',
			opacity: 2,
			stroke: 'red',
			strokeWidth: 3,
			strokeUniform: true
		});
		canvas.add(circle2).setActiveObject(circle2);
	}
	function addArrow() {
		imgInstance.selectable = false;
		addText(1);
		$("#disable_arrow").val(0);
	}
	function captureImage(is_upload = 0) {
		var imgageData = canvas.toDataURL("image/png");
		$("#base_64_img").val(imgageData);
		return true;
	}
</script>
@endif
@endpush