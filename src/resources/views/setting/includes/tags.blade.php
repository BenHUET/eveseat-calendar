<div class="box box-info">
	<div class="box-header with-border">
		<h3 class="box-title"><i class="fa fa-tags"></i> {{ trans('calendar::seat.tags') }}</h3>
	</div>
	<div class="box-body">

		<ul class="nav nav-pills"> 
			<li role="presentation" class="active">
				<a href="#new_tag" aria-controls="new_tag" role="tab" data-toggle="tab">{{ trans('calendar::seat.new') }}</a>
			</li> 
			<li role="presentation">
				<a href="#delete_tag" aria-controls="delete_tag" role="tab" data-toggle="tab">{{ trans('calendar::seat.delete') }}</a>
			</li>
		</ul>

		<div class="tab-content">

			<div role="tabpanel" class="tab-pane active" id="new_tag">
				<form class="form-horizontal" method="POST" action="{{ route('setting.tag.create') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label for="name" class="col-sm-3 control-label">{{ trans('calendar::seat.name') }}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="name" id="tag_name" placeholder="{{ trans('calendar::seat.name_tag_placeholder') }}" maxlength="7">
						</div>
					</div>

					<div class="form-group">
						<label for="order" class="col-sm-3 control-label">{{ trans('calendar::seat.order') }}</label>
						<div class="col-sm-9">
							<input type="number" class="form-control" name="order" id="tag_order" placeholder="{{ trans('calendar::seat.order_placeholder') }}">
						</div>
					</div>

					<div class="form-group">
						<label for="bg_color" class="col-sm-3 control-label">{{ trans('calendar::seat.background') }}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="bg_color" id="tag_bg_color" placeholder="{{ trans('calendar::seat.background_placeholder') }}" maxlength="7">
						</div>
					</div>

					<div class="form-group">
						<label for="text_color" class="col-sm-3 control-label">{{ trans('calendar::seat.text_color') }}</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="text_color" id="tag_text_color" placeholder="{{ trans('calendar::seat.text_color_placeholder') }}" maxlength="7">
						</div>
					</div>

					<div class="form-group">
						<label for="preview" class="col-sm-3 control-label">{{ trans('calendar::seat.preview') }}</label>
						<div class="col-sm-9">
							<small class="label tag" style="background-color: black" id="tag_preview">TAG</small>
						</div>
					</div>

					<button type="submit" class="btn btn-info pull-right">{{ trans('calendar::seat.save') }}</button>

				</form>
			</div>

			<div role="tabpanel" class="tab-pane" id="delete_tag">
				<table class="table table-striped table-condensed">
					<tr>
						<th>
							{{ trans('calendar::seat.preview') }}
						</th>
						<th>
							{{ trans('calendar::seat.order') }}
						</th>
						<th>
							{{ trans('calendar::seat.actions') }}
						</th>
					</tr>
					@foreach($tags->sortBy('order') as $tag)
						<tr class="tr-hoverable">
							<td>
								@include('calendar::common.includes.tag', ['tag' => $tag])
							</td>
							<td>
							{{ $tag->order }}
							</td>
							<td>
								<span class="clickable pull-right">
									<i class="fa fa-trash text-danger" data-toggle="modal" data-tag-id="{{ $tag->id }}" data-target="#modalConfirmDelete"></i>
								</span>
							</td>
						</tr>
					@endforeach
				</table>
			</div>

		</div>
		
	</div>
</div>