@extends('layouts.apex')
@section('body_class',' pace-done')
@section('title',trans('categories.label.categories'))

@push('css')
<link href="https://fonts.googleapis.com/css?family=David+Libre&display=swap" rel="stylesheet">
<style>
	body {
		font-family: 'David Libre', serif !important;
	}
	.display_in_print {
		display: none !important;
	}
	.content-wrapper {
		display: block !important;
		background-color: transparent;

	}
	.footer {
		position: inherit !important;
	}

	p {
		text-align: right;
	}
	@page: left {
		@bottom-left {
			content: "Page " counter(page) " of " counter(pages);
		}
	}
	@media print {
		.new-page {
			page-break-before: always;
		}
		img {
			page-break-inside: avoid;
		}
		.hide-on-print {
			visibility: hidden;
			heigh: 0px;
			margin: 0px;
			padding: 0px !important;
			margin: 0px !important;
		}
		.displaynone_on_print,
		.footer {
			display: none !important;
			padding: 0px !important;
			margin: 0px !important;
		}
		.display_in_print {
			display: block !important;
			padding: 0px !important;
			margin: 0px !important;
		}
		.app-sidebar {
			display: none !important;
			padding: 0px !important;
			margin: 0px !important;
		}
		.content-wrapper,
		.main-content,
		.main-panel,
		.margin0-on-print,
		.content-header,
		.card,
		.pl-3,
		.px-3 {
			padding: 0px !important;
			margin: 0px !important;
		}
		pre {
			border: 0;
		}
		body {
			-webkit-print-color-adjust: exact;
		}
		html,body {
			background-color: #fff;
		}
	}
</style>
@endpush
@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="content-header"> @lang('categories.label.categories') </div>
	</div>
</div>
<section id="configuration" class="categories-section">
	<div class="row">
		<div class="col-12">
			<div class="card">
				<div class="card-header">

				</div>
			</div>
		</div>
	</div>
	<div class="row match-height">
		<div class="col-lg-12 col-xl-12">
			<div id="accordionWrap1" role="tablist" aria-multiselectable="true">
				<div class="card collapse-icon accordion-icon-rotate">
					@php( $cat_count = 0 )
					@foreach($items as $item)
					<div id="heading{{$item->id}}" class="card-header">
						<a href="{{ url('/admin/categories/' . $item->id) }}" title="@lang('tooltip.common.icon.eye')">
							<span class="text-warning" id="category_{{$item->id}}">{{$item->display_order}} </span>
						</a>
						<a href="#accordion{{$item->id}}" class="card-title lead collapsed"> {{$item->name}}</a>
					</div>
					<div id="accordion{{$item->id}}">
						<div class="card-body">
							<div class="card-block">
								<table class="table table-bordered">
									@php( $cat_count = $cat_count + $item->fullchild->count() + 1 )
									@if($item->fullchild)
									@foreach($item->fullchild as $sub)
									<tr>
										<td colspan="2" class="width-65" id="category_{{$sub->id}}">
											<i class="text-success"><span class="text-warning">{{$item->display_order}}.{{$sub->display_order}} </span> {{$sub->name}}</i>
										</td>
									</tr>
									@if($sub->cost_detail && $sub->cost_detail !="")
									<tr>
										<td></td>
										<td>
											<div style="word-break: break-all; max-width:800px;">
												{!! $sub->cost_detail !!}
											</div>
										</td>
									</tr>
									@endif
									@if($sub->issue_detail && $sub->issue_detail !="")
									<tr>
										<td></td>
										<td>
											<div style="word-break: break-all; max-width:800px;">
												{!! $sub->issue_detail !!}
											</div>
										</td>
									</tr>
									@endif
									@if($sub->recommendation && $sub->recommendation !="")
									<tr>
										<td></td>
										<td>
											<div style="word-break: break-all; max-width:800px;">
												{!! $sub->recommendation !!}
											</div>
										</td>
									</tr>
									@endif
									@if($sub->quote && $sub->quote !="")
									<tr>
										<td></td>
										<td>
											<div style="word-break: break-all; max-width:800px;">
												{!! $sub->quote !!}
											</div>
										</td>
									</tr>
									@endif
									@if($sub->refefile->count())
									<tr>
										<td></td>
										<td>
											@php( $img_quote = [] )

											@if($sub->quote_desc && $sub->quote_desc != "")
											@php( $img_quote = json_decode($sub->quote_desc,true) )
											@endif

											@foreach($sub->refefile as $rf)
											@if($rf->file_thumb_url && $rf->file_thumb_url != "")
											<div class="row">
												<div class="col-sm-6">
													<a class="example-image-link " href="{!! $rf->file_url !!}" data-lightbox="example-2" data-title="{{$rf->refe_file_real_name}}">
														<img src="{!! $rf->file_thumb_url !!}" class="pull-right" height="80" style="max-width:200px" />
													</a>
												</div>
												<div class="col-sm-6" style="margin-top: 18px; ">
													@if(isset($img_quote[$rf->id]))
													<div style="word-break: break-all;max-width:400;">
														{!! $img_quote[$rf->id] !!}
													</div>
													@endif
												</div>
											</div>
											@endif
											@endforeach
										</td>
									</tr>
									@endif
									@endforeach
									@endif
								</table>
							</div>
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('js')
<script type="text/javascript">
	$(".print_report").click(function() {
		window.print();
	});

	@if(Request::has('print') && Request::get('print') == 1)
	//window.print();
	@endif
</script>
@endpush