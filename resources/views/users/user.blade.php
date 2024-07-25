@extends('layouts.admin.master')

@section('content')

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header card-dark ">
				<div class="card-tools">
					<div class="btn-groups" data-toggle="btn-toggle">
						<span id="addEmlTmpt" class="d-inline-block">
							<a href="javascript:valid(0)" data-toggle="modal" data-target="#add-edit-menu-modal" id="createNewProduct" class="btn btn-block bg-gradient-secondary btn-sm mr-2" title="Add User">
								<i class="fas fa-plus"></i> Add User
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
								<th>User Name</th>
								<th>User Email</th>
								<th>Status</th>
								<th>created At</th>
								<th>Updated At</th>
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
				<h5 id="title" class="modal-title"><span id="modal-name"></span> Category</h5>
				<button type="button" id="modal-dismiss" data-dismiss="modal" class="close"><small>&#10005;</small></button>
			</div>
			<form id="menu-add-edit-form" method="post" enctype="multipart/form-data">
				@csrf
				<input type="hidden" name="id" id="id" />
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>User Name</label>
								<input type="text" id="name" placeholder="User Name" name="name" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>User email</label>
								<input type="text" id="email" placeholder="User Email" name="email" class="form-control" value="" />
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Password </label>
								<input type="password" id="password" name="password" class="form-control" placeholder="Password" value="" />
								
							</div>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<div class="form-group required">
								<label>Confirm Password</label>
								<input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Confirm Password" value="" />
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
	var apiUrl = '{{route("api.listUsers")}}';
	var datatableColumns = [
		{
			data:'name',
			name:'users.name'
		},
		{
			data:'email',
			name:'users.email'
		},
		{
			data:'status',
			name:'users.status',
			render:function(data, row){
				return (data == 1)?'Active':'In-Active';
			}
		},
		{
			data:'created_at',
			name:'users.created_at',
			render:function(data, row){
				moment.defaultFormat = "DD.MM.YYYY HH:mm";
				return moment(data).format('DD-MM-YYYY hh:mm:ss A');
				// return date.format('D-M-YYYY hh:mm:ss A');
			}
		},
		{
			data:'updated_at',
			name:'users.updated_at',
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
		selector:'id',
		validate:'optional'
	},
	{
		type:'text',
		selector:'name',
		validate:'required'
	},
	{
		type:'text',
		selector:'email',
		validate:'required'
	},
	{
		type:'select',
		selector:'status',
		default:'1',
		validate:'required'
	},
	{
		type:'password',
		selector:'password',
		validate:'optional',
		dependency:'password_confirm',
	},
	{
		type:'password',
		selector:'password_confirm',
		validate:'optional',
		dependency:'password'
	}
	];

	$(function(){
		$('#menu-add-edit-form').on('submit', function(e){
			e.preventDefault();
			var data = $(this).serialize();
			if( validateU(($(this))) ){
				sendAjaxRequest("{{ route('api.save-user')}}", data, function(xhr){
					if(xhr.success){
						$('#successMsg').html(xhr.message);
						$('#sucess').toast('show');
						setDatatable( tableId ); 
						$('#add-edit-menu-modal').modal('hide');
					}else{
						$('#errorMsg').html(xhr.message);
						$('#error').toast('show');
						$.each(xhr.errors, function(elem, msg){
							// console.log(elem, msg);
							let error ='<div id="'+elem+'_error" class="text-danger">'+msg[0]+'</div>';
							// $('#'+elem).parent().append(error);
							$(error).insertAfter('#'+elem);
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

		const validateU = function (data){
			let valide = true;
			$.each(frmFields, function(i, fld){
				let fldId = '#'+fld.selector;
				let value = data.find(fldId).val();
				let fldValide = true;
				if(fld.validate ==='required' ){
					$(fldId+'_error').remove();
					if( !value ){
						$('<div id="'+fld.selector+'_error" class="text-danger ">Field is required.</div>').insertAfter(fldId);
						fldValide = valide = false;
					}

				}else if(fld.validate === 'optional' && fld.dependency !== "" ){
					let depId = '#'+fld.dependency;
					let depVal = $(depId).val();
					if( fld.type === 'password' ){
						$(depId+'_error').remove();
						if( (value.trim() != depVal.trim()) ){
							$('<div id="'+fld.dependency+'_error" class="text-danger ">Password not matched.</div>').insertAfter(depId);
							fldValide = valide = false;
							$(depId).addClass('border-danger');
						}else{
							$(depId).removeClass('border-danger');
						}
					}
				}

				if( !fldValide ){
					$(fldId).addClass('border-danger');
				}else{
					$(fldId).removeClass('border-danger');
				}

			});
			return valide;
		}

	});

</script>

@endsection