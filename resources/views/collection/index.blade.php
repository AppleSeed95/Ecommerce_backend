@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="javascript::void(0);" id="createNewCollection" class="btn btn-block bg-gradient-secondary btn-sm mr-2" title="Add Collection" data-toggle="modal" data-target="#add-edit-menu-modal" onclick="$('#modal-name').html('Add');">
								<i class="fas fa-plus"></i> Add Collection
							</a>
						</span>
					</div>
				</div>
			</div>
			
			<div class="card-body">
				<div class="table-responsive">
					<table id="datatable-table" class="table table-sm table-bordered table-striped w-100 freez-col">
						<thead class="thead-grey">
							<tr>
								<th>Collection Name</th>
								<th>Slug</th>
								<th>Image</th>
								<th>Meta Title</th>
								<th>Meta Desc.</th>
								<th>Updated AT</th>
								<th>Status</th>
								<th>Actions</th>
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
				<h5 id="title" class="modal-title"><span id="modal-name"></span> Collection</h5>
				<button type="button" id="modal-dismiss" data-dismiss="modal" class="close"><small>&#10005;</small></button>
			</div>
			<form id="menu-add-edit-form" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" id="id" />
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Collection Name</label>
								<input type="text" id="collection_name" placeholder="Collection Name" name="collection_name" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Slug</label>
								<input type="text" id="slug" placeholder="Slug" name="slug" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-12 ">
							<div class="form-group required">
								<label>Collection Desciption </label>
								<textarea id="collection_desc" class="form-control" name="collection_desc" placeholder="Collection Desciption"></textarea>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Collection Meta Title</label>
								<input type="text" id="meta_title" name="meta_title" class="form-control" placeholder="Collection Meta Title" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Collection Meta Desciption</label>
								<textarea id="meta_desc" name="meta_desc" class="form-control" placeholder="Collection Meta Description"></textarea>
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Collection Image</label>
								<input type="file" id="collection_image" name="collection_image" class="form-control" placeholder="Collection Image" accept="image/*" />
							</div>
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Status </label>
								<select id="status" name="status" class="form-control noTagSelect2">
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
	var apiUrl = '{{route("api.listCollection")}}';
	var datatableColumns = [
		{
			data:'collection_name',
			name:'collection_name'
		},
		{
			data:'slug',
			name:'slug',
		},
		{
			data:'collection_image',
			name:'collection_image',
			// className:'text-right'
			render:function(data, row){
				return '<img src="'+data+'" class="img-fluid img-thumbnail" width="100px" />';
			}
		},
		{
			// data:'route_name',
			// name:'menu.route_name',
			data:'meta_title',
			name:'meta_title',
		},
		{
			data:'meta_desc',
			name:'meta_desc'
		},
		{
			data:'updated_at',
			name:'updated_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
		},
		{
			data:'status',
			name:'status',
			render: function(data, row){
				if( data === 1){
					return 'Active';
				}else{
					return 'In-Active';
				}
			}
		},
		{
			data:'action',
			name:'action',
			searchable:false,
			orderable: false,
			can_export: false,
		}
	];
	var tableId  = 'datatable-table';
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
		selector:'collection_name'
	},
	{
		type:'img',
		selector:'collection_image'
	},
	{
		type:'text',
		selector:'slug',
	},
	{
		type:'text',
		selector:'meta_desc',
		default:''
	},
	{
		type:'text',
		selector:'meta_title'
	},
	{
		type:'editor',
		selector:'collection_desc',
		default:''
	},
	{
		type:'select',
		selector:'status',
		default:'1'
	},
	// {
	// 	type:'select',
	// 	selector:'visibilty',
	// 	default:'yes'
	// }
	];

	$(function(){

		// Summernote
	    $('#collection_desc').summernote({
		  	height: 150,   //set editable area's height
		  	// minHeight: 250,             // set minimum height of editor
		});

	    $('#collection_name').on('keyup', function(){
	    	$('#slug').val(getSlug($(this).val()));
	    });

		$('#menu-add-edit-form').on('submit', function(e){
			e.preventDefault();
			// var data = $(this).serialize();
			const formElement = document.querySelector("form");
			// const formElement = document.getElementById("menu-add-edit-form");
			var data = new FormData(this);
			if( collection_image.files[0] ){
				console.log(collection_image.files[0]);
				console.log(collection_image.files[0].name);
				data.append('collection_image', collection_image.files[0]);
			}
			console.log(data);
			if( validate(($(this).serialize())) ){
				sendAjaxRequestFiles("{{ route('api.save-collection')}}", data, function(xhr){
					if(xhr.success){
						$('#successMsg').html(xhr.message);
						$('#sucess').toast('show');
						setDatatable( tableId ); 
						$('#add-edit-menu-modal').modal('hide');
					}else{
						$('#errorMsg').html(xhr.message);
						$('#error').toast('show');
						$(document).find('div.errors').remove();
						$.each(xhr.errors, function(elem, msg){
							console.log(elem, msg);
							let error ='<div id="error-'+elem+'" class="errors text-danger">'+msg[0]+'</div>';
							$('#'+elem).parent().append(error);
							$('#'+elem).addClass('hasError');
						});
					}
				});
			}
		});

		setDatatable( tableId );

		$('#add-edit-menu-modal').on('hide.bs.modal', function (e) {
		  	// $('#menu-add-edit-form').reset();
		  	resetFrms(frmFields);
		  	$('#collection_desc').summernote('code','');
		});


		$('#slug, #display_name').on('keyup', function(){
			if(seoUrlEdit == false)
				$('#slug').val(getSlug($(this).val()));
		});
		$('#slug').on('keyup', function(){
			$('#slug').val(getSlug($(this).val()));
		});
		$('#slug').on('keypress', function() {
			seoUrlEdit = true;
			// $('#seo_url').val(getSlug($(this).val()));
		});




	});

</script>

@endsection