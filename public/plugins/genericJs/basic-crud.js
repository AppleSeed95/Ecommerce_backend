var dtDom = 'lfrtip';
var datatblObj;
var filterRequest = {};

var datatableProps = {
	destroy: true,
	pageLength: 25,
	menuLenght: [[25,50,100,200,500,-1],[25,50,100,200,500,'All']],
	deferRender: true,
	processing:true,
	language:{
		'processing':'<img src="" title="Loading..." alt="Loading..." style="z-index:9999"/>'
	},
	serverSide:true,
	dom: dtDom,
	ajax: {
        url: apiUrl,
        type:"POST",
        data: typeof filterRequest ==='Undefined' ?{}:filterRequest
    },
    columns: datatableColumns,
};

// $.fn.dataTable.ext.errMode = 'throw';


function setDatatable( tableId = '' ){
	if( tableId !='' ){
		tableElem = $('#'+tableId);
	}

	// object exists then clear it 
	if($.fn.dataTable.isDataTable(tableElem)){
		$(tableElem).empty();
	}
	datatblObj = tableElem.DataTable(datatableProps);
}



//
function validate(frmData){
	return true;
}

function sendAjaxRequest(url, data, callback){
	$.ajax({
		// headers:{
		// 	"Authorization": "Bearer "+token,
		// 	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// },
		url: url,
		data: data,
		type:'POST',
		beforeSend: function(){
			$('.preloader').css({height:'100%'});
			$('.preloader img').show();
		},
		success: function(xhr){
			$('.preloader').css({height:'0'});
			$('.preloader img').hide();
			if( typeof callback == 'function'){
				callback(xhr);
			}
		},
		error: function(xhr){
			
		},
		complete:function(xhr){
			$('.preloader').css({height:'0'});
			$('.preloader img').hide();
		}
	});
	
}

function sendAjaxRequestFiles(url, data, callback){
	$.ajax({
		// headers:{
		// 	"Authorization": "Bearer "+token,
		// 	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		// },
		url: url,
		data: data,
		type:'POST',
		processData: false,
		contentType: false,
		beforeSend: function(){
			$('.preloader').css({height:'100%'});
			$('.preloader img').show();
		},
		success: function(xhr){
			$('.preloader').css({height:'0'});
			$('.preloader img').hide();
			if( typeof callback == 'function'){
				callback(xhr);
			}
		},
		error: function(xhr){
			
		},
		complete:function(xhr){
			$('.preloader').css({height:'0'});
			$('.preloader img').hide();
		}
	});
	
}



function destroyRecord(id, url) {
	if(confirm("Are you sure you want to delete the record?")){
		$('.preloader').css({height:'100%'});
		$('.preloader img').show();
		sendAjaxRequest(url, {menuId:id}, function(xhr){
			if(xhr.success){
				$('#successMsg').html(xhr.message);
				$('#sucess').toast('show');
				setDatatable( tableId );
			}else{
				$('#errorMsg').html(xhr.message);
				$('#error').toast('show');
			}

			$('.preloader').css({height:'0%'});
			$('.preloader img').hide();

		});
	}
}

function destroyRow(id, url) {
	$('.preloader').css({height:'100%'});
	$('.preloader img').show();

	sendAjaxRequest(url, {recordId:id}, function(xhr){
		if(xhr.success){
			$('#successMsg').html(xhr.message);
		}else{
			$('#errorMsg').html(xhr.message);
		}

		$('.preloader').css({height:'0%'});
		$('.preloader img').hide();
		return xhr.success;
	});
}

function convertToSlug(text) {
	return text.toLowerCase()
	.replace(/[^a-zA-Z0-9]+/g, "-");
}

function fillModelBoxFrmData(data){
	// popUpName.modal('show');
	// $('#myModal').modal('show')
	// $('#menu-add-edit-form').modal('hide');
	$('#modal-name').html('Edit');
	$('#'+popUpName).modal({backdrop: 'static', keyboard: false},'show');
	// console.log(frmFields);
	$.each(frmFields, function(i,field){
		// console.log(data[field.selector]);
		let value = field.default;
		if(data[field.selector] != '' ){
			value = data[field.selector];
		}
		if( 'text'== field.type ){
			$('#'+field.selector).val(value);
		}else if( 'number'== field.type ){
			$('#'+field.selector).val(value);
		}else if( 'editor' == field.type){
			$('#'+field.selector).summernote('code', value);
		}else if( 'select' == field.type){
			// console.log(field.selector, value);
			$('#'+field.selector).val(value).trigger('change');
		}
	});
}

function resetFrms(frmFields){
	$.each(frmFields, function(i, obj){
		let selector = $('#'+obj.selector);
		let defaultVal = obj.default ?? '';
		if( obj.type == 'select'){
			selector.val('');
			selector.trigger('change');
		}else if( obj.type =='text' ){
			selector.val('');
		}else if( obj.type =='number' ){
			selector.val('');
		}
	});
}

function getSlug(string) {
	return string
      .toLowerCase()
      .replace(/[^a-z0-9-]/g, '-') // Replace non-alphanumeric characters with hyphens
      .replace(/-+/g, '-') // Replace multiple hyphens with a single hyphen
      .replace(/^-|-$/g, ''); // Remove hyphens from the beginning and end
}