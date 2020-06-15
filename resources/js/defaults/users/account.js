
		var accountId;
		function userDetails(id){

			accountId = id;

			let editAcc = document.querySelector('#--edit-account');
			let loading = document.querySelector('#--account-loading');

			editAcc.style.display = "none";
			loading.style.display = "block";

			axios.get(`/ajax/users/accounts/${ id }`)
				.then((response) => {

					loading.style.display = "none";
					editAcc.style.display = "block";

					let accountDetails = response.data;

					console.log(accountDetails);

					document.querySelector('#--e-profile-img-hidden').value = accountDetails['profile_img'];
					document.querySelector('#--e-profile-img').setAttribute('src', accountDetails['profile_img']);
					
					document.querySelector('#id_e_account_name').value = accountDetails['account_name'];
					document.querySelector('#id_e_type').value = accountDetails['type'];

					document.querySelector('#id_e_address').value = accountDetails['address'];
					
					document.querySelector('#id_e_contact_no').value = accountDetails['contact_no'];
					document.querySelector('#id_e_contact_person').value = accountDetails['contact_person'];

				});

		}

	$(document).ready(function(){

		$('#form-add-account').submit(function(e){

			e.preventDefault();

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add');
			var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-add');

			axios.post('/ajax/users/accounts', {
				'_token' : $('input[name="_token"]').val(),
				'profile_img' : $('input[name="profile_img"].for-add').val(),
				'account_name' : $('input[name="account_name"].for-add').val(),
				'type' : $('select[name="type"].for-add').val(),
				'address' : $('input[name="address"].for-add').val(),
				'contact_no' : $('input[name="contact_no"].for-add').val(),
				'contact_person' : $('input[name="contact_person"].for-add').val(),
			})
			.then((response) => {
				console.log(response.data);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => element.value = '');

				closeModal('add-account-modal');
				getAccountsViaAjax();

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

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update');
			var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-update');

			axios.patch(`/ajax/users/accounts/${ accountId }`, {
				'_token' : $('input[name="_token"]').val(),
				'profile_img' : $('input[name="profile_img"].for-update').val(),
				'account_name' : $('input[name="account_name"].for-update').val(),
				'type' : $('select[name="type"].for-update').val(),
				'address' : $('input[name="address"].for-update').val(),
				'contact_no' : $('input[name="contact_no"].for-update').val(),
				'contact_person' : $('input[name="contact_person"].for-update').val(),
			})
			.then((response) => {
				console.log(response.data);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => element.value = '');

				closeModal('update-account-modal');
				getAccountsViaAjax();

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
					if(key === 'type')
						$('select[name="type"].for-update').after('<label class="error-message for-update">'+value+'</label>');
					else
						$('input[name="'+key+'"].for-update').after('<label class="error-message for-update">'+value+'</label>');
				})
			});

		});

	});
