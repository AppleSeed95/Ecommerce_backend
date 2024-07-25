@extends('layouts.admin.master')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="javascript::void(0);" id="createNewMenu" class="btn btn-sm mr-2" title="Add Banner" data-toggle="modal" data-target="#add-edit-menu-modal" onclick="$('#modal-name').html('Add');">
								<i class="fas fa-plus"></i> Add Banner
							</a>
						</span>
					</div>
				</div>
			</div>
			{{ pr($validator ?? '') }}
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" action="{{ route('save-banner') }}">
					@csrf
					<div class="row">
						@if( $banner)
						<div class="col-lg-6 offset-6">
							<img src="{{$banner->banner_image}}" width="150" />
							<input type="hidden" name="id" id="id" value="{{ $banner->id }}">
						</div>
						@endif
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner Name</label>
							<input type="text" name="banner_name" class="form-control" id="banner_name" value="{{ old('banner_name', $banner->banner_name) }}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner Image</label>
							<input type="file" accept="image/*" name="banner_image" class="form-control" id="banner_image">
							
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner Group</label>
							<!-- <input type="text" name="banner_group" class="form-control" id="banner_group" value="{{old('banner_group', $banner->banner_group)}}"> -->
							<select name="banner_group" class="form-control" id="banner_group" >
								@foreach( $bannerGroup as $v )
								<option value="{{$v}}" @if( $banner->banner_group && $banner->banner_link == $v)  selected @endif>{{$v}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner URL</label>
							<input type="text" name="banner_link" class="form-control" id="banner_link" value="{{ old('banner_link', $banner->banner_link) }}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner Type</label>
							<select name="banner_type" class="form-control" id="banner_type">
								<option value="-1" @if( !$banner->banner_type) selected @endif >Please Select</option>
								<option value="banner" @if( $banner->banner_type == 'banner') selected @endif>Banner</option>
								<option value="cards" @if( $banner->banner_type == 'cards') selected @endif>Cards</option>
								<option value="carousel" @if( $banner->banner_type == 'carousel') selected @endif>Carousel</option>
							</select>

						</div>
						<div class="col-lg-12 col-sm-12 form-group">
							<label class="">Banner HTML</label>
							<textarea name="banner_html" id="banner_html" class="form-control">{{ old('banner_html', $banner->banner_html) }}</textarea>
						</div>

						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner Sequence</label>
							<input type="number" name="sequence" class="form-control" id="sequence" value="{{old('sequence', $banner->sequence)}}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Banner Status</label>
							<select name="status" class="form-control" id="status">
								<option value="1" selected >Active</option>
								<option value="0">In-Active</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 text-right">
							<a href="{{ route('banners') }}" class="btn btn-default" title="Cancel">Cancel</a>
							<button name="save" id="save-button" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
						</div>
					</div>
				</form> 
			</div>
		</div>
	</div>
</div>


@endsection

@section('script')

<script>
	$(function () {
	    // Summernote
	    $('#banner_html').summernote();
	    $('#banner_group').select2({
	    	// closeOnSelect : false,
	    	allowClear : true,
			placeholder : "Please select",
	    	tags: true
	    	// tokenSeparators: [',', ' ']
	    });
	});

</script>

@endsection