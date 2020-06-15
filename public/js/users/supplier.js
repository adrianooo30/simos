
var supplierId;

function userDetails(id){
	supplierId = id;

	let loading = new ModalLoading('#edit-supplier-modal');
	loading.setLoading();

	axios.get(`/ajax/users/suppliers/${ id }`)
		.then( (response) => {

			loading.removeLoading();

			let supplierInfo = response.data;

			console.log(supplierInfo);

			let inputField = {
				'profile_img' : $('[name="profile_img_hidden"].for-update-supplier'),
				'supplier_name' : $('[name="supplier_name"].for-update-supplier'),
				'contact_no' : $('[name="contact_no"].for-update-supplier'),
				'address' : $('[name="address"].for-update-supplier'),
			};

			$.each(inputField, (key, object) => {
				if(key == 'profile_img') {
					object.val(supplierInfo[key]);
					$('[name="profile_img"].for-update-supplier ~ .dropify-preview .dropify-render img').attr('src', supplierInfo[key]);
				}
				else
					object.val(supplierInfo[key]);
			} );
		});
}


$(document).ready(function(){

	$('#supplier-add-form').submit(function(e){

		e.preventDefault();

		axios.post('/ajax/users/suppliers', new FormData(this))
		.then((response) => {
			console.log(response.data);

			// allErrorMessagesDOM.forEach((element) => element.remove() );
			// allInputFieldsDOM.forEach((element) => element.value = '');

			suppliersTable.ajax.reload();

			// sweet alert
			successAlert(response);

		})
		.catch((error) => {

			errorAlert(error);

			console.log(error.response);

			let errors = error.response.data.errors;

			allErrorMessagesDOM.forEach((element) => element.remove());

			$.each(errors, (key, value) => {
				$('input[name="'+key+'"].for-add').after('<label class="error-message for-add">'+value+'</label>');
			})
		});

	});

	/*
		Update Form AJAX Reqest
	*/

	$('#supplier-update-form').submit(function(e){

		e.preventDefault();

		// var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update');
		// var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-update');

		axios.post(`/ajax/users/suppliers/${ supplierId }`, new FormData(this))
		.then((response) => {
			console.log(response.data);

			// allErrorMessagesDOM.forEach((element) => element.remove() );
			// allInputFieldsDOM.forEach((element) => element.value = '');

			// closeModal('update-supplier-modal');
			// getSuppliersViaAjax();

			suppliersTable.ajax.reload();

			// sweet alert
			successAlert(response);

		})
		.catch((error) => {

			errorAlert(error);
			
			let errors = error.response.data.errors;

			console.log(error.response);

			allErrorMessagesDOM.forEach((element) => element.remove());

			$.each(errors, (key, value) => {
				$('input[name="'+key+'"].for-update').after('<label class="error-message for-update">'+value+'</label>');
			})
		});

	});

});
