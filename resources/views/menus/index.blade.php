@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="javascript::void(0);" id="createNewMenu" data-backdrop="static" data-keyboard="false" class="btn btn-block bg-gradient-secondary btn-sm mr-2" title="Add Menu" data-toggle="modal" data-target="#add-edit-menu-modal" onclick="$('#modal-name').html('Add');">
								<i class="fas fa-plus"></i> Add Menu
							</a>
						</span>
					</div>
				</div>
			</div>
			<div id="notifyMsg" class=""></div>
			<div class="card-body">
				<div class="table-responsive">
					<table id="menu-table" class="table table-sm table-bordered table-striped w-100 freez-col">
						<thead class="thead-grey">
							<tr>
								<th>Parent Name</th>
								<th>Name</th>
								<th class="text-right">Sort Order</th>
								<th>Page URL</th>
								<th>Icon</th>
								<th>Updated AT</th>
								<th>Status</th>
								<th>Menu Visible</th>
								<th>Actions</th>
								<!-- <th></th> -->
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="add-edit-menu-modal" role="dialog" class="modal fade" data-keyboard="false" data-barkdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header py-2 modalHeaderCustom">
				<h5 id="title" class="modal-title"><span id="modal-name"></span> Menu</h5>
				<button type="button" id="modal-dismiss" data-dismiss="modal" class="close"><small>&#10005;</small></button>
			</div>
			<form id="menu-add-edit-form" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" id="id" />
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Name</label>
								<input type="text" id="display_name" placeholder="Enter Name" name="display_name" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>SEO URL</label>
								<div class="row">
									<div class="col-lg-4" id="link-type">
										<input type="text" readonly name="link_type" id="link_type" value="" class="form-control border-0" />
									</div>
									<div class="col-lg-8"><input type="text" id="seo_url" placeholder="Enter SEO URL" name="seo_url" class="form-control" value="" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Menu Group (<small>Defines the here it will be used.</small>) </label>
								<input type="text" id="menu_type" placeholder="Enter Name" name="menu_type" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Page URL</label>
								<select id="route_name" name="route_name" class="form-control select2">
									<option value="#">Blank URL</option>
									@foreach($availableRoutes as $key=>$routes)
									<?php //pr($availableRoutes[$key]); ?>
									@if( is_array($availableRoutes[$key]))
										<optgroup label="{{ $key }}">
										@foreach($routes as $routeKey=>$route)
										<option value="{{$routeKey}}">{{$route}}</option>
										@endforeach
										</optgroup>
									@else
										<option value="{{$routes}}">{{$routes}}</option>
									@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Menu Icon</label>
								<input type="text" id="icon" name="icon" class="form-control" placeholder="Enter Icon" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Menu Sort Order</label>
								<input type="number" id="sort_order" name="sort_order" class="form-control" placeholder="Enter Sort Order" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Select Parent Menu</label>
								<select id="parent_id" name="parent_id" class="form-control select2">
									<option value="">Select Parent Menu</option>
									@foreach(getMenus() as $route)
										<option value="{{$route->id}}">{{$route->display_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Set Visibilty </label>
								<select id="visibilty" name="visibilty" class="form-control">
									<option value="">Select Visiblility</option>
										<option value="yes" selected>Yes</option>
										<option value="no">No</option>
								</select>

							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Status </label>
								<select id="status" name="status" class="form-control select2">
									<option value="">Select status</option>
										<option value="1" selected>Active</option>
										<option value="0">In-Active</option>
								</select>

							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-dismiss="modal" id="close" class="btn btn-light mr-2"><span class="fas fa-times mr-1"></span> Cancel</button>
					<button type="submit" id="menu-submit-btn" class="btn btn-primary"><span class="fas fa-save mr-1"></span> Save</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('script')
<script>
	var apiUrl = '{{route("api.listMenu")}}';
	var datatableColumns = [
		{
			data:'name',
			name:'parent.display_name'
		},
		{
			data:'display_name',
			name:'menus.display_name',
		},
		{
			data:'sort_order',
			name:'menus.sort_order',
			className:'text-right'
		},
		{
			// data:'route_name',
			// name:'menu.route_name',
			data:'seo_url',
			name:'menus.seo_url',
		},
		{
			data:'icon'
		},
		{
			data:'updated_at',
			name:'menus.updated_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
		},
		{
			data:'status',
			name:'menus.status',
			render: function(data, row){
				if( data === 1){
					return 'Active';
				}else{
					return 'In-Active';
				}
			}
		},
		{
			data:'visibility',
			name:'menus.visibility',
			className: 'text-capitalize'
		},
		{
			data:'action',
			name:'action',
			searchable:false,
			orderable: false,
			can_export: false,
		}
	];
	var tableId  = 'menu-table';
	const popUpName = 'add-edit-menu-modal';
</script>
<script src="{{ asset('plugins/genericJs/basic-crud.js')}}"></script>

<script>
	var seoUrlEdit = false;
	var frmFields = [
	{
		type:'text',
		selector:'id'
	},
	{
		type:'text',
		selector:'display_name'
	},
	{
		type:'text',
		selector:'seo_url'
	},
	{
		type:'text',
		selector:'menu_type',
	},
	{
		type:'select',
		selector:'route_name',
		default:''
	},
	{
		type:'text',
		selector:'icon'
	},
	{
		type:'number',
		selector:'sort_order',
		default:''
	},
	{
		type:'select',
		selector:'parent_id',
		default:''
	},
	{
		type:'select',
		selector:'status',
		default:'1'
	},
	{
		type:'select',
		selector:'visibilty',
		default:'yes'
	}
	];


	// Do this before you initialize any of your modals
	// $.fn.modal.Constructor.prototype.enforceFocus = function() {};

	$(function(){

		//Initialize Select2 Elements
	    $('.select2').select2({
	    	dropdownParent: $('#add-edit-menu-modal')
	    });

		$('#menu-add-edit-form').on('submit', function(e){
			e.preventDefault();
			var data = $(this).serialize();
			console.log(data);
			if( validate(($(this).serialize())) ){
				sendAjaxRequest("{{ route('api.save-menu')}}", data, function(xhr){
					if(xhr.success){
						$('#notifyMsg').html(xhr.message).addClass('alert alert-success').removeClass('hide');
						// $('#sucess').toast('show');
						setDatatable( tableId ); 
						
						$('#add-edit-menu-modal').modal('hide');
					}else{
						$('#notifyMsg').html(xhr.message).addClass('alert alert-danger').removeClass('hide');
						$.each(xhr.errors, function(elem, msg){
							let error ='<div id="error-'+elem+'" class="text-danger">'+msg[0]+'</div>';
							$('#'+elem).parent().append(error);
							$('#'+elem).addClass('hasError');
						});
					}
					var load = setTimeout(function() {
						$('#notifyMsg').addClass('hide');
					}, 5000);
				});
			}
		});

		setDatatable( tableId );

		$('#add-edit-menu-modal').on('hide.bs.modal', function (e) {
		  	// $('#menu-add-edit-form').reset();
		  	resetFrms(frmFields);
		});

		$('#add-edit-menu-modal').on('shown.bs.modal', function (e) {
		  	$('.select2').trigger('change');
		});

		$('#seo_url, #display_name').on('keyup', function(){
			if(seoUrlEdit == false)
				$('#seo_url').val(convertToSlug($(this).val()));
		});
		// $('#seo_url').on('keyup', function(){
		// 	$('#seo_url').val(convertToSlug($(this).val()));
		// });
		$('#seo_url').on('keypress', function() {
			seoUrlEdit = true;
			// $('#seo_url').val(convertToSlug($(this).val()));
		});

		$('#route_name').on('change', function() {
			let otpGr = $(this).find('option:selected').parent();
			let text = '';
			// console.log(otpGr.attr('label'));
			if(otpGr.attr('label') == 'categories'){
				text = 'collections';
			}else if(otpGr.attr('label') == 'products'){
				text = 'products';
			}else if(otpGr.attr('label') == 'pages'){
				text = 'pages';
			}
			$('#link_type').val(text);
			$('#seo_url').val( $(this).val() );
		});


	});
</script>

@endsection