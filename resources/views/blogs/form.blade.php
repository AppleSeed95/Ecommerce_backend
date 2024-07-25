@extends('layouts.admin.master')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			
			{{ pr($validator ?? '') }}
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" action="{{ route('save-blog') }}">
					@csrf
					<div class="row">
						@if( $blog) 
						<div class="col-lg-6 offset-6">
							<img src="{{$blog->blog_image}}" width="150" />
							<input type="hidden" name="id" id="id" value="{{ $blog->id }}">
						</div>
						@endif
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Blog Title</label>
							<input type="text" maxlength="250" name="blog_title" class="form-control" id="blog_title" value="{{ old('blog_title', $blog->blog_title) }}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Slug</label>
							<input type="text" maxlength="255" name="blog_slug" @if( $blog->id !='' ) readonly @endif class="form-control" id="blog_slug" value="{{ old('blog_slug', $blog->blog_slug) }}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Meta Title</label>
							<input type="text" maxlength="180" name="meta_title" class="form-control" id="meta_title" value="{{ old('meta_title', $blog->meta_title) }}" />
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Meta Description</label>
							<textarea name="meta_desc" class="form-control" id="meta_desc" >{{old('meta_desc', $blog->meta_desc)}}</textarea>
						</div>
						<div class="col-lg-12 col-sm-12 form-group">
							<label class="">Blog Description</label>
							<textarea name="description" id="description" class="form-control">{{ old('description', $blog->description) }}</textarea>
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Blog Status</label>
							<select name="status" class="form-control" id="status">
								<option value="1" selected >Active</option>
								<option value="0">In-Active</option>
							</select>
						</div>
						
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Tags</label>
							<input name="tags" class="form-control" id="tags">
							<!-- <select name="tags[]" class="form-control" multiple="multiple" id="tags">
							</select> -->
						</div>
						<div class="col-lg-6 form-group blog-images dynamicRows" id="blog-images">
							<label class="">Blog Image</label>
							<input type="file" name="image" class="form-control">
						</div>

					</div>
					<div class="row">
						<div class="col-lg-12 text-right">
							<a href="{{ route('blogs') }}" class="btn btn-default" title="Cancel">Cancel</a>
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
	var apiUrl  ='';
	var datatableColumns = [];
</script>
<script src="{{ asset('plugins/genericJs/basic-crud.js')}}"></script>
<script>
	$(function () {
	    // Summernote
		$('#description').summernote({
		  	height: 300,   //set editable area's height
		  	// minHeight: 250,  // set minimum height of editor
		  });

		$(document).on('click','.addmore', function(e){
			var element = $(this);
			var parentID = $(this).parent().parent().parent().attr('id');
			alert(parentID);
			let imgCounter1 = $('div#'+parentID+' div.row').last().find('button').attr('data-index');
			imgCounter1= 1+ parseInt(imgCounter1);
			console.log('imgCounter1',imgCounter1);
	    	// let imgCounter = $('div.blog-images').length;
	    	// console.log(imgCounter);
			var rowId = 'prodImg_'+element.attr('data-index');
	    	// let htmlRow = $('#'+rowId).clone();
			let htmlRow = '';
			let row = '#'+parentID+' div.row';
			htmlRow = $(row).first().clone();
			htmlRow.find('button').attr('data-index', imgCounter1);
	    	//empty the textboxes and image
			htmlRow.find('input').attr('value', '');
			htmlRow.find('img').attr('src','');
			htmlRow.find('input[type="hidden"]').remove();
			htmlRow.find('button.btn-danger').removeAttr('data-remove');
			console.log(htmlRow);
			htmlRow = htmlRow[0].outerHTML;
			htmlRow = htmlRow.replace(/(_\d)/gm, '_'+imgCounter1).replace(/(\[\d\])/gm, '['+ imgCounter1 +']');

	    	// console.log(htmlRow);
	    	// $('div.blog-images').last().after(htmlRow);
			$('div#'+parentID).append(htmlRow);
	    	// .replace();

		});

		$(document).on('click', '.remove', function(e){
	    	// alert('remove');
			var element = $(this);
			var parentID = $(this).parent().parent().parent().attr('id');

			let rowId = parentID+'_'+element.attr('data-index');
			let imageLen = $('#'+parentID+' > .rowHover').length; 
			let imgId = element.attr('data-remove');
			alert(imageLen);
			if(imageLen > 1){
				if(imgId){
					if(confirm('Are you sure you want to delete image? Will not be undo after delete!...')){
						if(destroyRow(imgId, "{{route('api.destroyBlogImg')}}")){
							$('#'+rowId).remove();
						}
					}
				}else{
					$('#'+rowId).remove();
				}
			}else{

			}
		});

		@if( empty($blog->toArray()) )
		$('#blog_title, #blog_slug').on('keyup', function(){
			$('#blog_slug').val(getSlug($(this).val()));
		});
		@endif

		// $("#tags").select2({
		// 	tags: true,
		//     tokenSeparators: [',', ' '],
		//     maximumSelectionSize: 3,
		//     selectOnBlur: true,
		//     placeholder: 'Add or select tag',
		//     insertTag: function (data, tag) {
		// 	    // Insert the tag at the end of the results
		// 	    data.push(tag);
		//   	}
		// });

	});


	function getSlug(string) {
		return string
		.toLowerCase()
      .replace(/[^a-z0-9-]/g, '-') // Replace non-alphanumeric characters with hyphens
      .replace(/-+/g, '-') // Replace multiple hyphens with a single hyphen
      .replace(/^-|-$/g, ''); // Remove hyphens from the beginning and end
    }
  </script>

  @endsection