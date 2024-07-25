@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="{{ route('productFrm') }}" id="createNewProduct" class="btn btn-block bg-gradient-secondary btn-sm mr-2" title="Add Product">
								<i class="fas fa-plus"></i> Add Product
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
								<th>Product Name</th>
								<th>Product Image</th>
								<th>Slug</th>
								<th>SKU</th>
								<th>Meta Title</th>
								<th class="text-right">Price</th>
								<th class="text-right">Special Price</th>
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
	var apiUrl = '{{route("api.listProducts")}}';
	var datatableColumns = [
		{
			data:'product_name',
			name:'products.product_name'
		},
		{
			data:'product_image',
			name:'products.product_image',
			render:function(data, row){
				return '<img src="'+data+'" class="img-fluid img-thumbnail imagePrview" width="100px" />';
			}
		},
		{
			data:'product_slug',
			name:'products.product_slug',
		},
		{
			data:'sku',
			name:'products.sku',
		},
		{
			data:'meta_title',
			name:'products.meta_title',
		},
		{
			data:'price',
			name:'products.price',
			className:'text-right'
		},
		{
			data:'special_price',
			name:'products.special_price',
			className:'text-right'
		},
		{
			data:'status',
			name:'products.status',
			render:function(data, row){
				return (data == 1)?'Active':'In-Active';
			}
		},
		{
			data:'created_at',
			name:'products.created_at',
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

		// $.each($('img.imagePrview'), function(i, elem){
		// 	let e = $(elem);
		// 	if(e.attr('src')=='' || e.attr('src')=='/storage/'){
		// 		e.attr('src', '{{asset("img/image.png")}}');
		// 	}
		// });
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