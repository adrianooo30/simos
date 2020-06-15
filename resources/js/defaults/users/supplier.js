
	var supplierId;

	function userDetails(id){
		supplierId = id;

		let editSupplier = document.querySelector('#--edit-supplier');
		let loading = document.querySelector('#--supplier-loading');

		loading.style.display = "block";
		editSupplier.style.display = "none";

		axios.get(`/ajax/users/suppliers/${ id }`)
			.then( (response) => {
				editSupplier.style.display = "block";
				loading.style.display = "none";

				let supplier = response.data;

				document.querySelector('#id_e_supplier_name').value = supplier['supplier_name'];
				document.querySelector('#id_e_contact_no').value = supplier['contact_no'];
				document.querySelector('#id_e_address').value = supplier['address'];

				document.querySelector('#--e-profile-img-hidden').value = supplier['profile_img'];
				document.querySelector('#--e-profile-img').setAttribute('src', supplier['profile_img']);
			});
	}


	$(document).ready(function(){

		$('#form-add-supplier').submit(function(e){

			e.preventDefault();

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add');
			var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-add');

			axios.post('/ajax/users/suppliers', {
				'_token' : $('input[name="_token"]').val(),
				'profile_img' : $('input[name="profile_img"].for-add').val(),
				'supplier_name' : $('input[name="supplier_name"].for-add').val(),
				'address' : $('input[name="address"].for-add').val(),
				'contact_no' : $('input[name="contact_no"].for-add').val(),
			})
			.then((response) => {
				console.log(response.data);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => element.value = '');

				closeModal('add-supplier-modal');
				getSuppliersViaAjax();

				// sweet alert
				swal({
					title : response.data['title'],
					text : response.data['text'],
					icon : 'success',
					button : {
						text : 'Okay',
						value : true,
						visible : true,
						className : '',
						closeModal : true,
					},
				});

			})
			.catch((error) => {

				errorAlert(error);

				let errors = error.response.data.errors;

				console.log(errors);

				allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					$('input[name="'+key+'"].for-add').after('<label class="error-message for-add">'+value+'</label>');
				})
			});

		});

		/*
			Update Form AJAX Reqest
		*/

		$('#form-update-supplier').submit(function(e){

			e.preventDefault();

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update');
			var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-update');

			axios.patch(`/ajax/users/suppliers/${ supplierId }`, {
				'_token' : $('input[name="_token"]').val(),
				'profile_img' : $('input[name="profile_img"].for-update').val(),
				'supplier_name' : $('input[name="supplier_name"].for-update').val(),
				'address' : $('input[name="address"].for-update').val(),
				'contact_no' : $('input[name="contact_no"].for-update').val(),
			})
			.then((response) => {
				console.log(response.data);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => element.value = '');

				closeModal('update-supplier-modal');
				getSuppliersViaAjax();

				// sweet alert
				swal({
					title : response.data['title'],
					text : response.data['text'],
					icon : 'success',
					button : {
						text : 'Okay',
						value : true,
						visible : true,
						className : '',
						closeModal : true,
					},
				});

			})
			.catch((error) => {

				errorAlert(error);
				
				let errors = error.response.data.errors;

				console.log(errors);

				allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					$('input[name="'+key+'"].for-update').after('<label class="error-message for-update">'+value+'</label>');
				})
			});

		});

	});
