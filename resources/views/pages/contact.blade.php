@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="{{ route('pageFrm') }}" id="createNewPage" class="btn btn-block bg-gradient-secondary btn-sm mr-2" title="Add Page">
								<i class="fas fa-plus"></i> Add Page
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
								<th>Name</th>
								<th>Phone</th>
								<th>Email</th>
								<th>Need Installation</th>
								<th>Postcode</th>
								<th>Product name</th>
								<th>created At</th>
								<th>Updated At</th>
								<!-- <th>Actions</th> -->
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
	const tableId = 'datatable-table';
	var apiUrl = '{{route("api.contact-us")}}';
	var datatableColumns = [
		{
			data:'contact_name',
			name:'contact_us.contact_name'
		},
		{
			data:'contact_phone',
			name:'contact_us.contact_phone',
			render:function(data, row){
				return '<a href="tel:'+data+'" title="Telephone" class="" />'+data+'</a>';
			}
		},
		{
			data:'contact_email',
			name:'contact_us.email',
			render:function(data, row){
				return '<a href="mailto:'+data+'" title="Email" class="" />'+data+'</a>';
			}
		},
		{
			data:'contact_install',
			name:'contact_us.contact_install'
		},
		{
			data:'zip',
			name:'contact_us.zip',
		},
		{
			data:'product_name',
			name:'contact_us.product_name'
		},
		{
			data:'created_at',
			name:'pages.created_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
		},
		{
			data:'updated_at',
			name:'menu.updated_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
		}
		// {
		// 	data:'action',
		// 	name:'action',
		// 	searchable:false,
		// 	orderable: false,
		// 	can_export: false,
		// }
	];
	const popUpName = 'add-edit-menu-modal';
</script>
<script src="{{ asset('plugins/genericJs/basic-crud.js')}}"></script>

<script>
	var seoUrlEdit = false;
	
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