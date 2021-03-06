var employeeId;

function userDetails(id){

	employeeId = id;

	let editEmployee = document.querySelector('#--edit-employee');
	let loading = document.querySelector('#--employee-loading');

	editEmployee.style.display = "none";
	loading.style.display = "block";

	axios.get(`/ajax/users/employees/${ id }`)
		.then((response) => {

			editEmployee.style.display = "block";
			loading.style.display = "none";

			console.log( response.data );

			let employee = response.data;

			$('#--e-profile-img-hidden').val(employee['profile_img']);
			$('#--e-profile-img').attr('src',employee['profile_img']);

			$('[name="fname"].for-update').val(employee['fname']);
			$('[name="mname"].for-update').val(employee['mname']);
			$('[name="lname"].for-update').val(employee['lname']);

			$('[name="position"].for-update').val(employee['position_id']);

			$('[name="contact_no"].for-update').val(employee['contact_no']);
			$('[name="email"].for-update').val(employee['email']);
			$('[name="address"].for-update').val(employee['address']);

			$('[name="username"].for-update').val(employee['username']);
			$('[name="password"].for-update').val(employee['password']);

		});
}

	$('#form-add-employee').submit(function(e){

		e.preventDefault();

		var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add');
		var allInputFieldsDOM = document.querySelectorAll('.input-box .for-add');

		axios.post('/ajax/users/employees', {
			'_token' : $('[name="_token"]').val(),

			'profile_img' : $('[name="profile_img"].for-add').val(),

			'fname' : $('[name="fname"].for-add').val(),
			'mname' : $('[name="mname"].for-add').val(),
			'lname' : $('[name="lname"].for-add').val(),
			'position_id' : $('[name="position"].for-add').val(),

			'contact_no' : $('[name="contact_no"].for-add').val(),
			'email' : $('[name="email"].for-add').val(),
			'address' : $('[name="address"].for-add').val(),

			'username' : $('[name="username"].for-add').val(),
			'password' : $('[name="password"].for-add').val(),
		})
		.then((response) => {
			console.log(response.data);

			allErrorMessagesDOM.forEach((element) => element.remove() );
			allInputFieldsDOM.forEach((element) => element.value = '');

			closeModal('add-employee-modal');

			// reload datatables
			employeesTable.ajax.reload();

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

			console.log( error );

			errorAlert(error);

			let errors = error.response.data.errors;

			allErrorMessagesDOM.forEach((element) => element.remove());

			$.each(errors, (key, value) => {
				if(key === 'position')
					$('select[name="position"].for-add').after('<label class="error-message for-add">'+value+'</label>');
				else
					$('input[name="'+key+'"].for-add').after('<label class="error-message for-add">'+value+'</label>');
			})
		});

	});

	/*
		Update Form AJAX Reqest
	*/

	$('#form-update-employee').submit(function(e){

		e.preventDefault();

		var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update');
		var allInputFieldsDOM = document.querySelectorAll('.input-box .for-update');

		axios.patch(`/ajax/users/employees/${ employeeId }`, {
			'_token' : $('[name="_token"]').val(),
			'profile_img' : $('[name="profile_img"].for-update').val(),

			'fname' : $('[name="fname"].for-update').val(),
			'mname' : $('[name="mname"].for-update').val(),
			'lname' : $('[name="lname"].for-update').val(),

			'position_id' : $('select[name="position"].for-update').val(),
			'email' : $('[name="email"].for-update').val(),
			'contact_no' : $('[name="contact_no"].for-update').val(),

			'address' : $('[name="address"].for-update').val(),
			'username' : $('[name="username"].for-update').val(),
			'password' : $('[name="password"].for-update').val(),
		})
		.then((response) => {
			console.log(response.data);

			allErrorMessagesDOM.forEach((element) => element.remove() );
			allInputFieldsDOM.forEach((element) => element.value = '');

			closeModal('update-employee-modal');

			// reload datatables
			employeesTable.ajax.reload();

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

			console.log( error );

			errorAlert(error);
			
			let errors = error.response.data.errors;

			console.log(errors);

			allErrorMessagesDOM.forEach((element) => element.remove());

			$.each(errors, (key, value) => {
				if(key === 'position')
					$('select[name="position"].for-update').after('<label class="error-message for-update">'+value+'</label>');
				else
					$('input[name="'+key+'"].for-update').after('<label class="error-message for-update">'+value+'</label>');
			})
		});

	});


	// *******************************************************************************************
	//								SET PRODUCTS FOR EMPLOYEE 									//
	// *******************************************************************************************

	getDateToday('#--start-date');

	var setProductsData, setEmployeeData;

	function setEmployeeProductHolding(id)
	{
		employeeId = id;

		let content = $('#--set-products');
		let loading = $('#--modal-loading');

		content.hide();
		loading.show();

		axios.get(`/ajax/users/employees/${ id }/getProductsFor`)
			.then((response) => {

				content.show();
				loading.hide();

				console.log(response.data);

				refreshModal();

				// set employee info
				set_employeeInfo(response.data['employee']);

				// set employee data
				setEmployeeData = response.data['employee'];

				// set data to global
				setProductsData = response.data['set_products'];
				// set select box
				set_selectBoxProducts();
				// display the currently holding
				displayProductsHolded();
				// track if empty
				track_if_empty();

			});
	}

	function refreshModal()
	{
		processType_targetAmount = '';
		// empty the list
		productAddedList.empty();

		// refresh info for target amount
		$('#--info-for-target-amount').empty();

		$('#--btn-edit-target').show();
		$('#--btn-change-target').show();
		$('#--btn-cancel-target').hide();
	}

	function set_employeeInfo(employee)
	{
		$('#--full-name').html(`${ employee['full_name'] }`);
		$('#--position').html(`${ employee['position']['position_name'] }`);
		// target amount

		if( employee['target'].length > 0 ) {
			//
			$('.--btns-for-target-amount').show();
			//
			$('#--target-amount').val(`${ employee['latest_target']['target_amount'] }`).attr('readonly', '').attr('disabled', '');
		}
		else {
			//
			$('.--btns-for-target-amount').hide();
			//
			$('#--target-amount').val('').removeAttr('readonly').removeAttr('disabled');
		}
	}

	// set select box the options
	function set_selectBoxProducts()
	{
		let productSelectBoxes = $('#--select-box-product.for-set-product');
		// refresh the select
		$(productSelectBoxes).empty();
		// add prompt select product
		$(productSelectBoxes).append(`
			<option value="" disabled selected>--ADD PRODUCT--</option>
		`);
		// APPENDING ALL PRODUCTS NOT HOLDED
		setProductsData.forEach(function(product, index){
			// DISPLAY IN SELECT BOX - SINCE DIDNT HOLDED
			if( !product['holded'] ) {
				$(productSelectBoxes).append(`
					<option value="${ product['id'] }">${ product['generic_name'] } ${ product['strength'] } - ${ product['brand_name'] }</option>
				`);
			}
		});
	}

	//
	function displayProductsHolded()
	{
		$.each( setProductsData, (index, product) => {
			//
			if( product['holded'] ) {
				// append product to list
				appendThisList( product );
			}
		});
	}

	// product added list
	var productAddedList = $('#--added-product-list.for-set-product');
	// SINCE THIS IS ONLY IN CLIENT SIDE - TEMPORARY SELECTION 
	function addProductToBeHold(productId)
	{
		$.each( setProductsData, (index, product) => {
			//
			if( product['id'] == productId) {
				// set to true
				product['holded'] = true;
				// append product to list
				appendThisList( product );
			}
		});

		// REFRESH THE SELECT BOX FOR PRODUCTS
		set_selectBoxProducts();
		// track if empty
		track_if_empty();
	}

	// REMOVE PRODUCT IN LIST
	function removeProductToHold(productId)
	{
		// REMOVE IN HTML DISPLAY
		$( `#--product-holded-${ productId }` ).remove();
		// PRODUCT ID - HOLDED RETURN TO FALSE
		$.each( setProductsData, (index, product) => { product['id'] == productId ? product['holded'] = false : '' } )
		// REFRESH THE SELECT BOX FOR PRODUCTS
		set_selectBoxProducts();
		// track if empty
		track_if_empty();
	}

	// TRACK IF EMPTY OR NOT
	function track_if_empty()
	{
		console.log( `%c ${ productAddedList.find('li').length }`, 'color: green' );

		// if no selected
		if( productAddedList.find('li').length == 0 ) {
			// APPEND NOTHING IS SELECTED
			productAddedList.append(`
				<li class="list-group-item text-muted text-center" id="--product-list-none">
			    	No product currently holding yet.
			    </li>
			`);
		}

		// if have selected list
		else {
			$('#--product-list-none').remove();
		}
	}

	// append display
	function appendThisList( product )
	{
		// MINI CONDITIONAL
		currentHolder = `<span class="text-warning">--NO HOLDER--</span>`;
		if( product['has_current_holder'] ) {

			let classColor = 'text-danger';
			if( product['current_holder']['id'] == employeeId ) {
				classColor = 'text-primary';
			}

			currentHolder = `<i class="ti-user ${ classColor }"></i> <span class="text-muted bolder">${ product['current_holder']['full_name'] }</span>`;
		}

		// APPEND ALL PRODUCT CURRENTLY HOLDING
		productAddedList.append(`
			<li class="list-group-item text-primary d-flex justify-content-between" id="--product-holded-${ product['id'] }">
		    	<div class="d-flex flex-fill">
                    <img src="${ product['product_img'] }" alt="product_img" class="image-50 mx-1">

                    <div class="mx-1 flex-fill">
                        <h6 class="text-primary">${ product['generic_name'] } ${ product['strength'] }</h6>
                        <sup class="text-muted">${ product['brand_name'] }</sup><br>
                        <sup class="text-muted">
                        	${ currentHolder }
                        </sup>
                    </div>
                </div>
		    	<button type="button" class="btn btn-sm btn-outline-danger" onclick="removeProductToHold(${ product['id'] })">
		    		<i class="ti-close"></i>
		    	</button>
		    </li>
		`);

		console.log(product);
	}

	var processType_targetAmount;

	// BUTTON EDIT TARGET
	$('#--btn-edit-target').on('click', function(){
		processType_targetAmount = 'edit-target-amount';

		$('#--target-amount').removeAttr('readonly', '').removeAttr('disabled', '').attr('autofocus', '');

		// INFO DETAILS
		$('#--info-for-target-amount').html(`<sub class="text-warning"><i class="ti-info-alt"></i> Edit the target amount.</sub>`);

		// REMOVE BUTTON CHANGE TARGET
		$(this).hide('fast');
		$('#--btn-change-target').show('fast');

		// SHOW CANCEL
		$('#--btn-cancel-target').show('fast');
	});

	// BUTTON CHANGE TARGET
	$('#--btn-change-target').on('click', function(){
		processType_targetAmount = 'change-target-amount';

		// INFO DETAILS
		$('#--info-for-target-amount').html(`<sub class="text-primary"><i class="ti-info-alt"></i> Change the target amount.</sub>`);

		// REMOVE BUTTON CHANGE TARGET
		$(this).hide('fast');
		$('#--btn-edit-target').show('fast');

		$('#--target-amount').removeAttr('readonly', '').removeAttr('disabled', '').attr('autofocus', '');

		$('#--btn-cancel-target').show('fast');
	});

	// BUTTON CANCEL TARGET
	$('#--btn-cancel-target').on('click', function(){
		$(this).hide('fast');

		// refresh
		$('#--info-for-target-amount').empty();

		$('#--btn-edit-target').show('fast');
		$('#--btn-change-target').show('fast');

		$('#--target-amount').attr('readonly', '').attr('disabled', '').val( setEmployeeData['latest_target']['target_amount'] );
	});

	// SET PRODUCTS FORM
	$( '#--set-product-form' ).submit( function(e){

		e.preventDefault();

		// CONFIRMATION
			swal({
				title: 'Are you sure, you want to perform any changes?',
				text : 'This will cause any changes in the history of the former employee that holds the product. If yes, then click the confirm button to proceed.',
				icon : 'warning',
				buttons : true,
				dangerMode: true,
			})
			.then((confirmation) => {

				if(confirmation) {

					// var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-set-products');
					// var allInputFieldsDOM = document.querySelectorAll('.input-box .for-set-products');

					axios.post(`/ajax/users/employees/${ employeeId }/setProductsFor`, {
						'_token' : $('[name="_token"]').val(),

						// 'start_date' : $('[name="start_date"].for-set-products').val(),
						'target_amount' : $('[name="target_amount"].for-set-product').val(),
						'process_type_target_amount' : processType_targetAmount,

						'products' : setProductsData.filter((product) => { return product['holded']; }),
					})
					.then((response) => {
						console.log(response.data);

						// REFRESH EMPLOYEE PRODUCT LOADING
						setEmployeeProductHolding(employeeId);

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

						console.log(error.response);
						
					});

				}// END - willChange

			});

	});