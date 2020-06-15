
var accountId;
function userDetails(id)
{
	accountId = id;

	let loading = new ModalLoading('#edit-customer-account-modal');
	loading.setLoading();

	axios.get(`/ajax/users/accounts/${ id }`)
		.then((response) => {

			loading.removeLoading();

			let accountInfo = response.data;

			console.log(accountInfo);

			let inputField = {
				'profile_img' : $('[name="profile_img_hidden"].for-update-account'),
				'account_name' : $('[name="account_name"].for-update-account'),
				'type' : $('[name="type"].for-update-account'),
				'address' : $('[name="address"].for-update-account'),
				'contact_no' : $('[name="contact_no"].for-update-account'),
				'contact_person' : $('[name="contact_person"].for-update-account'),
			};

			$.each(inputField, (key, object) => {
				if(key == 'profile_img') {
					object.val(accountInfo[key]);
					$('[name="profile_img"].for-update-account ~ .dropify-preview .dropify-render img').attr('src', accountInfo[key]);
				}
				else
					object.val(accountInfo[key]);
			} );

		});
}

	$(document).ready(function(){

		$('#form-add-account').submit(function(e){

			e.preventDefault();

			// var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add');
			// var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-add');

			axios.post('/ajax/users/accounts', new FormData( this ))
			.then((response) => {
				console.log(response.data);

				// allErrorMessagesDOM.forEach((element) => element.remove() );
				// allInputFieldsDOM.forEach((element) => element.value = '');

				// sweet alert
				successAlert(response);

				// reload accounts table
				accountsTable.ajax.reload();

			})
			.catch((error) => {

				errorAlert(error);
				
				let errors = error.response.data.errors;

				console.log(errors);
				allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					if(key === 'type')
						$('select[name="type"].for-add').after('<label class="error-message for-add">'+value+'</label>');
					else
						$('input[name="'+key+'"].for-add').after('<label class="error-message for-add">'+value+'</label>');
				})
			});

		});

		/*
			Update Form AJAX Reqest
		*/

		$('#form-update-account').submit(function(e){

			e.preventDefault();

			// var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update');
			// var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-update');

			axios.post(`/ajax/users/accounts/${ accountId }`, new FormData(this))
			.then((response) => {
				console.log(response.data);

				// allErrorMessagesDOM.forEach((element) => element.remove() );
				// allInputFieldsDOM.forEach((element) => element.value = '');

				accountsTable.ajax.reload();

				successAlert( response );

			})
			.catch((error) => {

				errorAlert(error);

				let errors = error.response.data.errors;

				console.log(errors);

				// allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					if(key === 'type')
						$('select[name="type"].for-update').after('<label class="error-message for-update">'+value+'</label>');
					else
						$('input[name="'+key+'"].for-update').after('<label class="error-message for-update">'+value+'</label>');
				})
			});

		});

	});
