@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="{{ route('bannerFrm') }}" id="createNewBanner" class="btn  btn-block bg-gradient-secondary btn-sm mr-2" title="Add Banner">
								<i class="fas fa-plus"></i> Add Banner
							</a>
						</span>
					</div>
				</div>
			</div>


			<div class="card-body">
				@if( session('message') )
				<div class="alert alert-{{session('status')}}">
					{{ session('message') }}
				</div>
				@endif
				<div class="table-responsive">
					<table id="datatable-table" class="table table-sm table-bordered table-striped w-100 freez-col">
						<thead class="thead-grey">
							<tr>
								<th>Banner Name</th>
								<th>Banner Image</th>
								<th>Banner Group</th>
								<th>Page URL</th>
								<th>Banner HTML</th>
								<th>Banner Type</th>
								<th class="text-right">Sort Order</th>
								<th>Status</th>
								<th>created At</th>
								<th>Updated At</th>
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

@endsection

@section('script')
<script>
	var apiUrl = '{{route("api.listBanner")}}';
	var datatableColumns = [
		{
			data:'banner_name',
			name:'banners.banner_name'
		},
		{
			data:'banner_image',
			name:'banners.banner_image',
			render:function(data, row){
				return '<img src="'+data+'" class="img-fluid img-thumbnail" width="100px" />';
			}
		},
		{
			data:'banner_group',
			name:'banners.banner_group',
		},
		{
			data:'banner_link',
			name:'banners.banner_link',
		},
		{
			data:'banner_html',
			name:'banners.banner_html',
		},
		{
			data:'banner_type',
			name:'banners.banner_type',
		},
		{
			data:'sequence',
			name:'banners.sequence',
		},
		{
			data:'status',
			name:'banners.status',
			render:function(data, row){
				return (data == 1)?'Active':'In-Active';
			}
		},
		{
			data:'created_at',
			name:'banners.created_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
		},
		{
			data:'updated_at',
			name:'banners.updated_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
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
	const tableId  = 'datatable-table';
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

	$(function(){
		$('#menu-add-edit-form').on('submit', function(e){
			e.preventDefault();
			var data = $(this).serialize();
			console.log(data);
			if( validate(($(this).serialize())) ){
				sendAjaxRequest("{{ route('api.save-menu')}}", data, function(xhr){
					if(xhr.success){
						$('#successMsg').html(xhr.message);
						$('#sucess').toast('show');
						setDatatable( tableId ); 
						$('#add-edit-menu-modal').modal('hide');
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

		$('#add-edit-menu-modal').on('hide.bs.modal', function (e) {
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