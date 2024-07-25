@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="{{ route('blogFrm') }}" id="createNewBlog" class="btn btn-block bg-gradient-secondary btn-sm mr-2" title="Add Blog">
								<i class="fas fa-plus"></i> Add Blog
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
								<th>Blog Name</th>
								<th>Image</th>
								<th>Slug</th>
								<th>Meta Title</th>
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
	var apiUrl = '{{route("api.listBlogs")}}';
	var datatableColumns = [
		{
			data:'blog_title',
			name:'blogs.blog_title'
		},
		{
			data:'image',
			name:'blogs.image',
			render:function(data, row){
				return '<img src="'+data+'" class="img-fluid img-thumbnail" width="100px" />';
			}
		},
		{
			data:'blog_slug',
			name:'blogs.blog_slug',
		},
		{
			data:'meta_title',
			name:'blogs.meta_title',
		},
		{
			data:'status',
			name:'blogs.status',
			render:function(data, row){
				return (data == 1)?'Active':'In-Active';
			}
		},
		{
			data:'created_at',
			name:'blogs.created_at',
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