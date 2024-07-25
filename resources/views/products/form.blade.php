@extends('layouts.admin.master')

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			
			{{ pr($validator ?? '') }}
			<div class="card-body">
				<form method="post" enctype="multipart/form-data" action="{{ route('save-product') }}">
					@csrf
					<div class="row">
						@if( $product) 
						<div class="col-lg-6 offset-6">
							<img src="{{$product->product_image}}" width="150" />
							<input type="hidden" name="id" id="id" value="{{ $product->id }}">
						</div>
						@endif
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Product Name</label>
							<input type="text" maxlength="250" name="product_name" class="form-control" id="product_name" value="{{ old('product_name', $product->product_name) }}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Slug</label>
							<input type="text" maxlength="255" name="product_slug" @if( $product->id !='' ) readonly @endif class="form-control" id="product_slug" value="{{ old('product_slug', $product->product_slug) }}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Meta Title</label>
							<input type="text" maxlength="180" name="meta_title" class="form-control" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}" />
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Meta Description</label>
							<textarea name="meta_desc" class="form-control" id="meta_desc" >{{old('meta_desc', $product->meta_desc)}}</textarea>
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Product SKU</label>
							<input type="text" maxlength="25" name="sku" class="form-control" id="sku" value="{{ old('sku', $product->sku) }}" />
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Vendor Name</label>
							<input type="text" name="vendor_name" class="form-control" id="vendor_name" value="{{ old('vendor_name', $product->vendor_name) }}" />
						</div>
						<div class="col-lg-12 col-sm-12 form-group">
							<label class="">Product Description</label>
							<textarea name="product_desc" id="product_desc" class="form-control">{{ old('product_desc', $product->product_desc) }}</textarea>
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Product Price</label>
							<input type="number" name="price" class="form-control" id="price" value="{{old('price', $product->price)}}">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Product Special Price</label>
							<input type="number" name="special_price" class="form-control" id="special_price" value="{{old('special_price', $product->special_price)}}">
						</div>
						<div class="col-lg-4 col-sm-12 form-group">
							<label class="">Product Status</label>
							<select name="status" class="form-control" id="status">
								<option value="1" @if ($product->status==1) selected @endif >Active</option>
								<option value="0" @if ($product->status==0) selected @endif >In-Active</option>
							</select>
						</div>
						<div class="col-lg-4 col-sm-12 form-group">
							<label class="">Is Featured</label>
							<select name="is_featured" class="form-control" id="is_featured">
								<option value="1" @if ($product->is_featured==1) selected @endif >Yes</option>
								<option value="0" @if ($product->is_featured==0) selected @endif >No</option>
							</select>
						</div>
						<div class="col-lg-4 col-sm-12 form-group">
							<label class="">Availability</label>
							<input type="number" name="availability" class="form-control" id="availability">
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Categories</label>
							<select name="categories[]" multiple class="form-control" size="2" id="categories">
								@foreach($categories as $id=>$val)
									<option value="{{$id}}" 
									@if( in_array($id, $productCategories ))
										selected
									@endIf 
									>{{$val}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Collections</label>
							<select name="collections[]" multiple class="form-control" size="2" id="collections">
								@foreach($collections as $id=>$val)
									<option value="{{$id}}" 
									@if( in_array($id, $productCollections ))
										selected
									@endIf 
									>{{$val}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-6 col-sm-12 form-group">
							<label class="">Tags</label>
							<select name="tags" class="form-control" multiple="multiple" id="tags">
							</select>
						</div>
						<div class="col-lg-6 col-sm-12 form-group"></div>
						<div class="col-lg-12">
							<hr/>
						</div>
						<div class="col-lg-6 form-group">
							<label class="">Product Images</label>
							<hr/>
							<div class="row text-bold">
								<div class="col-md-4">
									Image
								</div>
								<div class="col-md-4">
									SEO Title
								</div>
								<div class="col-md-2">
									Image Prev.
								</div>
								<div class="col-md-2">
									
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 product-images dynamicRows" id="product-images">
								<?php
								$i = 0;
								// pr(count($product->productImages));
								do{
									?>
									<div class="row rowHover" id="product-images_{{$i}}">
										 @if(!empty($product->productImages[$i]->id)) 
										 <input type="hidden" name="product_image[{{$i}}][id]" value="{{$product->productImages[$i]->id}}" />  
										 @endIf
										<div class="col-md-4 form-group pt-2">
											<input type="file" name="product_image[{{$i}}][image]" class="form-control">
										</div>
										<div class="col-md-4 form-group pt-2">
											<input type="text" name="product_image[{{$i}}][title]" value="{{!empty($product->productImages[$i]->image_title)?$product->productImages[$i]->image_title:'';}}" class="form-control" />
										</div>
										<div class="col-md-2 form-group pt-2">
											<img src="{{!empty($product->productImages[$i]->image_path)?Storage::url($product->productImages[$i]->image_path):''}}" class="img-fluid">
										</div>
										<div class="col-md-2 text-right form-group pt-2 action">
											<button class="btn btn-danger remove btn-sm" data-index="{{$i}}" @if(!empty($product->productImages[$i]->id)) data-remove="{{$product->productImages[$i]->id}}" @endIf type="button"><span class="fa fa-minus"></span></button>
											<button class="btn btn-success addmore btn-sm" data-index="{{$i}}" type="button"><span class="fa fa-plus"></span></button>
										</div>
									</div>
								<?php
									++$i;
								}while($i < count($product->productImagesOnly));?>
								</div>
							</div>
						</div>
						<div class="col-lg-6 form-group">
							<label class="">Product Documents</label>
							<hr/>
							<div class="row text-bold">
								<div class="col-md-4">
									Upload Document
								</div>
								<div class="col-md-4">
									SEO Title
								</div>
								<div class="col-md-2">
									document
								</div>
								<div class="col-md-2">
									
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 product-doc dynamicRows" id="product-doc">
								<?php
								$i = 0;
								// pr(count($product->productImages));
								do{
									?>
									<div class="row rowHover" id="product-doc_{{$i}}">
										 @if(!empty($product->productDocsOnly[$i]->id)) 
										 <input type="hidden" name="product_doc[{{$i}}][id]" value="{{$product->productDocsOnly[$i]->id}}" />  
										 @endIf
										<div class="col-md-5 form-group pt-2">
											<input type="file" name="product_doc[{{$i}}][image]" class="form-control">
										</div>
										<div class="col-md-4 form-group pt-2">
											<input type="text" name="product_doc[{{$i}}][title]" value="{{!empty($product->productDocsOnly[$i]->image_title)?$product->productDocsOnly[$i]->image_title:'';}}" class="form-control" />
										</div>
										<div class="col-md-3 text-right form-group pt-2 action">
											@if( !empty( $product->productDocsOnly[$i]->image_path ) )
											<a href="{{Storage::url($product->productDocsOnly[$i]->image_path)}}" download="{{$product->productDocsOnly[$i]->file_name}}" class="btn btn-sm btn-primary"><span class="fas fa-download"></span></a>
											@endif
											<button class="btn btn-danger remove btn-sm" data-index="{{$i}}" @if(!empty($product->productDocsOnly[$i]->id)) data-remove="{{$product->productDocsOnly[$i]->id}}" @endIf type="button"><span class="fa fa-minus"></span></button>
											<button class="btn btn-success addmore btn-sm" data-index="{{$i}}" type="button"><span class="fa fa-plus"></span></button>
										</div>
									</div>
								<?php
									++$i;
								}while($i < count($product->productDocsOnly));?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12 text-right">
							<a href="{{ route('products') }}" class="btn btn-default" title="Cancel">Cancel</a>
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
	    $('#product_desc').summernote({
		  	height: 300,   //set editable area's height
		  	// minHeight: 250,             // set minimum height of editor
		});

	    $(document).on('click','.addmore', function(e){
	    	var element = $(this);
	    	var parentID = $(this).parent().parent().parent().attr('id');
	    	// alert(parentID);
	    	let imgCounter1 = $('div#'+parentID+' div.row').last().find('button').attr('data-index');
	    	imgCounter1= 1+ parseInt(imgCounter1);
	    	console.log('imgCounter1',imgCounter1);
	    	// let imgCounter = $('div.product-images').length;
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
	    	// $('div.product-images').last().after(htmlRow);
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
	    	// alert(imageLen);
	    	if(imageLen > 1){
		    	if(imgId){
		    		if(confirm('Are you sure you want to delete image? Will not be undo after delete!...')){
			    		if(destroyRow(imgId, "{{route('api.destroyProductImg')}}")){
			    			$('#'+rowId).remove();
			    		}
		    		}
		    	}else{
		    		$('#'+rowId).remove();
		    	}
	    	}else{

	    	}
	    });

	    @if( empty($product) )
		    $('#product_name').on('keyup', function(){
		    	$('#product_slug').val(getSlug($(this).val()));
		    });
		@endif

		$("#tags").select2({
		    tags: true,
		    tokenSeparators: [',', ' ']
		})
	    
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