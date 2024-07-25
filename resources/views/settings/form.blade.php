@extends('layouts.admin.master')

@section('content')
<style type="text/css">
	.card-header {background: #e5e5e5;border: 1px solid #c0c0c0;}
	.card-primary > .card-header {background: transparent;border: 0 solid #c0c0c0; height: 10px;}
	form .card {border-top: 1px solid #e8e8e8;}
</style>
<div class="row">
	<div class="col-12">
		@if (session('success'))
		<div class="alert alert-success">
			{{ session('success') }}
		</div>
		@endif
		<div class="card">
			
			{{ pr($validator ?? '') }}

			<div class="card-body">
				<form method="post" enctype="multipart/form-data" action="{{ route('save-sattings') }}">
					@csrf
					<div class="col-lg-12 text-right mb-3">
						<a href="{{ route('settings') }}" class="btn btn-default" title="Cancel">Cancel</a>
						<button name="save" id="save-button" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
					</div>
					<div class="card card-primary card-outline">
						<div class="card-header"></div>
						<div class="card-body">

							<ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" id="custom-content-below-home-tab" data-toggle="pill" href="#custom-content-below-home" role="tab" aria-controls="custom-content-below-home" aria-selected="true">All Settings</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" id="custom-content-below-profile-tab" data-toggle="pill" href="#custom-content-below-profile" role="tab" aria-controls="custom-content-below-profile" aria-selected="false">HomePage UI Settings</a>
								</li>
							</ul>
							<div class="tab-content" id="custom-content-below-tabContent">
								<div class="tab-pane fade show active p-2 pt-3" id="custom-content-below-home" role="tabpanel" aria-labelledby="custom-content-below-home-tab">
									
									<div class="card">
										<div class="card-header">
											<h3 class="card-title">Basic Settings</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool btn-default" data-card-widget="collapse"  title="Collapse"><i class="fas fa-minus"></i></button>
											</div>
										</div>
										{{-- @if($errors->any())
										{!! implode('', $errors->all('<div class="text-danger text-sm">:message</div>')) !!}
										@endif --}}
										<div class="card-body form-group">
											<div class="row">
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Site Title</label>
														</div>
														<div class="col-8">
															<input type="input" name="site_title" class="form-control" id="site_title" value="{{ old('site_title', $setting['site_title']??'') }}" />
															@error('site_title')
															<div class="text-danger text-sm">{{ $errors->first('site_title') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>SEO Title</label>
														</div>
														<div class="col-8">
															<input type="input" name="seo_title" class="form-control" id="seo_title" value="{{ old('seo_title', $setting['seo_title']??'') }}" />
															@if($errors->has('seo_title'))
															<div class="text-danger text-sm">{{ $errors->first('seo_title') }}</div>
															@endif
														</div>
													</div>
												</div>

												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>SEO Description</label>
														</div>
														<div class="col-8">
															<input type="input" name="seo_description" class="form-control" id="seo_description" value="{{ old('seo_description', $setting['seo_description']??'') }}" />
															@error('seo_description')
															<div class="text-danger text-sm">{{ $errors->first('seo_description') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>FB Page Link</label>
														</div>
														<div class="col-8">
															<input type="input" name="fb_page_link" class="form-control" id="fb_page_link" value="{{ old('fb_page_link', $setting['fb_page_link']??'') }}" />
															@error('fb_page_link')
															<div class="text-danger text-sm">{{ $errors->first('fb_page_link') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Twitter Page Link</label>
														</div>
														<div class="col-8">
															<input type="input" name="twitter_page_link" class="form-control" id="twitter_page_link" value="{{ old('twitter_page_link', $setting['twitter_page_link']??'') }}" />
															@error('twitter_page_link')
															<div class="text-danger text-sm">{{ $errors->first('twitter_page_link') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Youtube Page Link</label>
														</div>
														<div class="col-8">
															<input type="input" name="youtube_page_link" class="form-control" id="youtube_page_link" value="{{ old('youtube_page_link', $setting['youtube_page_link']??'') }}" />
															@error('youtube_page_link')
															<div class="text-danger text-sm">{{ $errors->first('youtube_page_link') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Contact Number</label>
														</div>
														<div class="col-8">
															<input type="input" name="contact_phone" class="form-control" id="contact_phone" value="{{ old('contact_phone', $setting['contact_phone']??'') }}" />
															@error('contact_phone')
															<div class="text-danger text-sm">{{ $errors->first('contact_phone') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Contact email</label>
														</div>
														<div class="col-8">
															<input type="input" name="contact_email" class="form-control" id="contact_email" value="{{ old('contact_email', $setting['contact_email']??'') }}" />
															@error('contact_email')
															<div class="text-danger text-sm">{{ $errors->first('contact_email') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Copyright Text</label>
														</div>
														<div class="col-8">
															<input type="input" name="copyright_text" class="form-control" id="copyright_text" value="{{ old('copyright_text', $setting['copyright_text']??'') }}" />
															@error('copyright_text')
															<div class="text-danger text-sm">{{ $errors->first('copyright_text') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-8">
													<div class="row border1 p-1">
														<div class="col-2">
															<label>Header Strip Text</label>
														</div>
														<div class="col-10">
															<input type="input" name="header_trip_text" class="form-control" id="header_trip_text" value="{{ old('header_trip_text', $setting['header_trip_text']??'') }}" />
															@error('header_trip_text')
															<div class="text-danger text-sm">{{ $errors->first('header_trip_text') }}</div>
															@enderror
														</div>
													</div>
												</div>
												<div class="col-4">
													<div class="row border1 p-1">
														<div class="col-4 pb-3">
															<label>Header Menu Group</label>
														</div>
														<div class="col-8">
															<select id="header_menu_group" name="header_menu_group" class="form-control select2">
																<option value="-1">Please select </option>
																@foreach($menuGroups as $group)
																	<option value="{{$group}}" @if ($group == $setting['header_menu_group']) selected @endif>{{$group}}</option>
																@endforeach
															</select>
														</div>
													</div>
												</div>


											</div>
										</div>
										<!-- /.card-body -->
										<!-- <div class="card-footer">Footer</div> -->
										<!-- /.card-footer-->
									</div>
									<div class="card">
										<div class="card-header">
											<h3 class="card-title">Images / Media</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool btn-default" data-card-widget="collapse"  title="Collapse"><i class="fas fa-minus"></i></button>
											</div>
										</div>
										<div class="card-body form-group">
											<div class="row">
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Site Logo</label>
														</div>
														<div class="col-7">											
															<input type="file" name="site_logo" class="form-control showImgPreview" id="site_logo" accept="image/*" />
														</div>
														<div class="col-2">
															<img src="{{$setting['site_logo']??''}}" class="img-fluid img-thumbnail imagePrview"  id="site_logo_preview"/>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Favicon</label>
														</div>
														<div class="col-7">											
															<input type="file" name="site_favicon" class="form-control showImgPreview" id="site_favicon" accept="image/*" />
														</div>
														<div class="col-2">
															<img src="{{$setting['site_favicon']??''}}" class="img-fluid img-thumbnail imagePrview"  id="site_favicon_preview"/>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Default Image</label>
														</div>
														<div class="col-7">											
															<input type="file" name="site_default_image" class="form-control showImgPreview" id="site_default_image" accept="image/*" />
														</div>
														<div class="col-2">
															<img src="{{$setting['site_default_image']??''}}" class="img-fluid img-thumbnail imagePrview"  id="site_default_image_preview"/>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Loader Image</label>
														</div>
														<div class="col-7">											
															<input type="file" name="site_loader_image" class="form-control showImgPreview" id="site_loader_image" accept="image/*" />
														</div>
														<div class="col-2">
															<img src="{{$setting['site_loader_image']??''}}" class="img-fluid img-thumbnail imagePrview"  id="site_loader_image_preview"/>
														</div>
													</div>
												</div>

											</div>
										</div>
										<!-- /.card-body -->
										<!-- <div class="card-footer">Footer</div> -->
										<!-- /.card-footer-->
									</div>

									<div class="card">
										<div class="card-header">
											<h3 class="card-title">Footer Content</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool btn-default" data-card-widget="collapse"  title="Collapse"><i class="fas fa-minus"></i></button>
											</div>
										</div>
										<div class="card-body form-group">
											<div class="row">
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Footer Contact Us</label>
														</div>
														<div class="col-9">											
															<textarea name="footer_contact" id="footer_contact" class="form-control">{{$setting['footer_contact']??''}}</textarea>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Footer Menu Group</label>
														</div>
														<div class="col-9">											
															<select id="footer_links" name="footer_links" class="form-control select2">
																<option value="-1">Please select </option>
																@foreach($menuGroups as $group)
																	<option value="{{$group}}" @if ($group == $setting['footer_links']) selected @endif>{{$group}}</option>
																@endforeach
															</select>
														</div>

													</div>
												</div>

											</div>
										</div>
										<!-- /.card-body -->
										<!-- <div class="card-footer">Footer</div> -->
										<!-- /.card-footer-->
									</div>


								</div>
								<div class="tab-pane fade p-2 pt-3" id="custom-content-below-profile" role="tabpanel" aria-labelledby="custom-content-below-profile-tab">
									

									<div class="card">
										<div class="card-header">
											<h3 class="card-title">RECENT PRODUCTS</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool btn-default" data-card-widget="collapse"  title="Collapse"><i class="fas fa-minus"></i></button>
											</div>
										</div>
										<div class="card-body form-group">
											<div class="row">
												<div class="col-12">
													<div class="row border1 p-1">
														<div class="col-2">
															<label>Select Catagories</label>
														</div>
														<div class="col-10">											
															<select name="recent_prodct_cats[]" multiple class="form-control" size="5" id="recent_prodct_cats">
																@if( !empty($categories))
																@foreach( $categories as $cat )
																<option value="{{$cat->id}}" @if(in_array( $cat->id, $setting['recent_prodct_cats'])) selected @endif >{{$cat->category_name}}</option>
																@endforeach
																@endif
															</select>
														</div>
														<div class="col-12">
															<div class="bg-orange disabled color-palette p-2 mt-1 text-sm"> Selected categories will be shown at the home page at "Recent product" section with 4-5 latest products. </div>
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="card">
										<div class="card-header">
											<h3 class="card-title">ABOUT AUSTRALIAN BOLLARDS</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool btn-default" data-card-widget="collapse"  title="Collapse"><i class="fas fa-minus"></i></button>
											</div>
										</div>
										<div class="card-body form-group">
											<div class="row">
												<div class="col-12">
													<div class="row border1 pl-1 pr-1">
														<div class="col-3">
															<label></label>
														</div>
														<div class="col-12">											
															<textarea name="about_au_bollard" id="about_au_bollard" class="form-control">{{ old('about_au_bollard', ($setting['about_au_bollard']??'')) }}</textarea>
														</div>
													</div>
												</div>
												<div class="col-12"><hr></div>
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Collection Banner</label>
														</div>
														<div class="col-7">											
															<input type="file" name="about_bollard_collection" class="form-control showImgPreview" id="about_bollard_collection" accept="image/*" />
														</div>
														<div class="col-2">
															<img src="{{$setting['about_bollard_collection']??''}}" class="img-fluid img-thumbnail imagePrview"  id="about_bollard_collection_preview"/>
														</div>
													</div>
												</div>
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-4">
															<label>Collection Banner Link</label>
														</div>
														<div class="col-8">											
															<select id="about_bollard_collection_link" name="about_bollard_collection_link" class="form-control select2">
																<option value="#">Blank URL</option>
																@foreach($availableRoutes as $key=>$routes)
																@if( is_array($availableRoutes[$key]))
																	<optgroup label="{{ $key }}">
																	@foreach($routes as $routeKey=>$route)
																	<option value="{{$routeKey}}" @if ($setting['about_bollard_collection_link']==$routeKey) selected @endif>{{$route}}</option>
																	@endforeach
																	</optgroup>
																@else
																	<option value="{{$routes}}" @if ($setting['about_bollard_collection_link']==$routes) selected @endif>{{$routes}}</option>
																@endif
																@endforeach
															</select>
														</div>
													</div>
												</div>
												<div class="col-12">
													<div class="row border1 mt-1 p-1">
														<div class="col-12">
															<label>About Australian Bollard Videos</label>
														</div>
														<div class="col-2">
															<label>YouTube Video</label>
														</div>
														<div class="col-4">											
															<input type="text" name="about_bollard_video[]" class="form-control showImgPreview" id="about_bollard_video1" value="{{ old('about_bollard_video.0', ($setting['about_bollard_video'][0]??'')) }}"  />
														</div>
														<div class="col-2">
															<label>YouTube Title</label>
														</div>
														<div class="col-4">
															<input type="text" name="about_bollard_video_title[]" class="form-control showImgPreview" id="about_bollard_video2" value="{{ old('about_bollard_video_title.1', ($setting['about_bollard_video_title'][1]??'')) }}"  />
														</div>
														<div class="col-2">
															<label>YouTube Video </label>
														</div>
														<div class="col-4">											
															<input type="text" name="about_bollard_video[]" class="form-control showImgPreview" id="about_bollard_video1" value="{{ old('about_bollard_video.0', ($setting['about_bollard_video'][0]??'')) }}"  />
														</div>
														<div class="col-2">
															<label>YouTube Title</label>
														</div>
														<div class="col-4">
															<input type="text" name="about_bollard_video_title[]" class="form-control showImgPreview" id="about_bollard_video2" value="{{ old('about_bollard_video_title.1', ($setting['about_bollard_video_title'][1]??'')) }}"  />
														</div>
													</div>
												</div>

											</div>
										</div>
									</div>

									<div class="card">
										<div class="card-header">
											<h3 class="card-title">Our Clients</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool btn-default" data-card-widget="collapse"  title="Collapse"><i class="fas fa-minus"></i></button>
											</div>
										</div>
										<div class="card-body form-group">
											<div class="row">
												<div class="col-6">
													<div class="row border1 p-1">
														<div class="col-3">
															<label>Select Client Logos</label>
														</div>
														<div class="col-7">											
															<input type="file" multiple="multiple" name="our_clients[]" class="form-control showImgPreview" id="our_clients" accept="image/*" />
														</div>
														<div class="col-2">
														</div>
													</div>
												</div>
												<div class="col-6"></div>
												@if(is_array($setting['our_clients']))

												<div class="col-12">
													<hr />
													<div class="row">
														@foreach($setting['our_clients'] as $client)
														<div class="col-1">
															<img src="{{$client??''}}" class="img-fluid img-thumbnail imagePrview"  id="site_logo_preview"/>
														</div>
														@endforeach
													</div>
												</div>
												@endif

											</div>
										</div>
									</div>

								</div>
							</div>

						</div>



						<div class="col-lg-12 text-right mb-2">
							<a href="{{ route('settings') }}" class="btn btn-default" title="Cancel">Cancel</a>
							<button name="save" id="save-button" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
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
			$.each($('.imagePrview'), function(i, elem){
				let e = $(elem);
				if(e.attr('src')=='' || e.attr('src')=='/storage/'){
					e.attr('src', '{{asset("img/image.png")}}');
				}
			});

			$('.showImgPreview').on('change', function(e){
				console.log(e);
			});

			if($(this).has('.alert-success')){
				setTimeout(function(){$('.alert-success').hide();}, 5000);
			}

			$('#recent_prodct_cats, #footer_links, #header_menu_group').select2({
				tags:true,
				tokenSeparators: [',', ' '],
				maximumSelectionSize: 3,
				selectOnBlur: true,
				placeholder: 'Add or select categories',

			});

		    // Summernote
		    $('#about_au_bollard').summernote({
			  	height: 300,   //set editable area's height
			  	// minHeight: 250,             // set minimum height of editor
			});
		    // Summernote
		    $('#footer_contact').summernote({
			  	height: 100,   //set editable area's height
			  	// minHeight: 250,             // set minimum height of editor
			});

		});
	</script>

	@endsection