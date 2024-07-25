@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="javascript::void(0);" id="createNewMenu" class="btn btn-sm mr-2" title="Add Menu" data-toggle="modal" data-target="#add-edit-ytVideos-modal" onclick="$('#modal-name').html('Add');">
								<i class="fas fa-plus"></i> Add Video
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
								<th>Video Title</th>
								<th>Video Link </th>
								<th class="text-right">Sort Order</th>
								<th class="text-right">Video Start At</th>
								<th>Created At</th>
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

<div id="add-edit-ytVideos-modal" role="dialog" class="modal fade" data-keyboard="false" data-barkdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header py-2 modalHeaderCustom">
				<h5 id="title" class="modal-title"><span id="modal-name"></span> Menu</h5>
				<button type="button" id="modal-dismiss" data-dismiss="modal" class="close"><small>&#10005;</small></button>
			</div>
			<form id="menu-add-edit-form">
				@csrf
				<input type="hidden" name="id" id="id" />
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Video Title</label>
								<input type="text" id="video_title" placeholder="Enter title" name="video_title" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Video URL</label>
								<input type="text" id="video_link" placeholder="Enter video URL" name="video_link" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Start At (<small>Video will start after number of seconds.</small>) </label>
								<input type="number" id="video_start" placeholder="Start at" name="video_start" class="form-control" value="0" />
							</div>
						</div>
						
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Sort Order</label>
								<input type="number" id="sort_order" name="sort_order" class="form-control" placeholder="Enter Sort Order" value="" />
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
						
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Delete </label>
								<select id="is_delete" name="is_delete" class="form-control noTagSelect2">
									<option value="1" >Yes</option>
									<option value="0" selected>No</option>
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
	var apiUrl = '{{route("api.listVideo")}}';
	var datatableColumns = [
		{
			data:'video_title',
			name:'video_title'
		},
		{
			data:'video_link',
			name:'video_link',
		},
		{
			data:'sort_order',
			name:'sort_order',
			className:'text-right'
		},
		{
			data:'video_start',
			name:'video_start',
			className:'text-right'
		},
		{
			data:'created_at',
			name:'created_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
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
	const popUpName = 'add-edit-ytVideos-modal';
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
		selector:'video_title'
	},
	{
		type:'text',
		selector:'video_link'
	},
	{
		type:'number',
		selector:'video_start',
	},
	{
		type:'select',
		selector:'is_delete',
		default:'0'
	},
	{
		type:'number',
		selector:'sort_order',
		default:''
	},
	{
		type:'select',
		selector:'status',
		default:'1'
	}
	];

	$(function(){
		$('#menu-add-edit-form').on('submit', function(e){
			e.preventDefault();
			var data = $(this).serialize();
			console.log(data);
			if( validate(($(this).serialize())) ){
				sendAjaxRequest("{{ route('api.save-video')}}", data, function(xhr){
					if(xhr.success){
						$('#successMsg').html(xhr.message);
						$('#sucess').toast('show');
						setDatatable( tableId ); 
						$('#add-edit-ytVideos-modal').modal('hide');
					}else{
						$('#errorMsg').html(xhr.message);
						$('#error').toast('show');
						$.each(xhr.errors, function(elem, msg){
							console.log(elem, msg);
							let error ='<div id="error-'+elem+'" class="text-danger">'+msg[0]+'</div>';
							$('#'+elem).parent().append(error);
							$('#'+elem).addClass('hasError');
						});
					}
				});
			}
		});

		setDatatable( tableId );

		$('#add-edit-ytVideos-modal').on('hide.bs.modal', function (e) {
		  	// $('#menu-add-edit-form').reset();
		  	resetFrms(frmFields);
		});
	});

	$('#seo_url, #display_name').on('keyup', function(){
		if(seoUrlEdit == false)
			$('#seo_url').val(convertToSlug($(this).val()));
	});
	$('#seo_url').on('keyup', function(){
		$('#seo_url').val(convertToSlug($(this).val()));
	});
	$('#seo_url').on('keypress', function() {
		seoUrlEdit = true;
		// $('#seo_url').val(convertToSlug($(this).val()));
	});
</script>

@endsection