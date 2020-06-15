
	// for all of the tabs their
	var tabs = ['details', 'batch-no', 'edit-info', 'add-batch-no', /*'set-holder'*/];
	// end of for all of the tabs their
		
	$(document).ready(function(){

		$('#product-add-form').submit(function(e){

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add');
			var allInputFieldsDOM = document.querySelectorAll('.input-box input.for-add');

			e.preventDefault();

			axios.post('/ajax/inventory/products', {
				'_token' : $('[name="_token"]').val(),
				'product_img' : $('[name="product_img"].for-add').val(),
				'generic_name' : $('[name="generic_name"].for-add').val(),
				'brand_name' : $('[name="brand_name"].for-add').val(),
				'product_unit' : $('[name="product_unit"].for-add').val(),
				'weight_volume' : $('[name="weight_volume"].for-add').val(),
				'strength' : $('[name="strength"].for-add').val(),
				'unit_price' : $('[name="unit_price"].for-add').val(),
				'critical_quantity' : $('[name="critical_quantity"].for-add').val(),
			})
			.then((response) => {

				console.log(response.data);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => element.value = '');

				closeModal('add-product-modal');
				productTable.ajax.reload();

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

				console.log(error.response);

				let errors = error.response.data.errors;

				allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					$(`[name="${ key }"].for-add`).after(`<label class="error-message for-add imp">${ value }</label>`);
				})

			});

		});

		$('#product-update-form').submit(function(e){

			e.preventDefault();

			swal({
				title: 'Are you sure, you want to perform any changes?',
				text : 'If yes, then click the confirm button to proceed.',
				icon : 'warning',
				buttons : true,
				dangerMode: true,
			})
			.then((willChange) => {

				if(willChange) {

					var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update');

					axios.patch(`/ajax/inventory/products/${ productId }`, {	
						'_token' : $('input[name="_token"]').val(),
						'product_img' : $('input[name="product_img"].for-update').val(),
						'generic_name' : $('input[name="generic_name"].for-update').val(),
						'brand_name' : $('input[name="brand_name"].for-update').val(),
						'product_unit' : $('input[name="product_unit"].for-update').val(),
						'weight_volume' : $('input[name="weight_volume"].for-update').val(),
						'strength' : $('input[name="strength"].for-update').val(),
						'unit_price' : $('input[name="unit_price"].for-update').val(),
						'critical_quantity' : $('input[name="critical_quantity"].for-update').val(),
					})
					.then((response) => {

						console.log(response.data);

						allErrorMessagesDOM.forEach((element) => element.remove() );

						// product table reload
						productTable.ajax.reload();

						productInfo(productId);

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

						console.log(error.response);

						let errors = error.response.data.errors;

						allErrorMessagesDOM.forEach((element) => element.remove());

						$.each(errors, (key, value) => {
							$(`[name="${ key }"].for-update`).after(`<label class="error-message for-update imp">${ value }</label>`);
						})

					});
				}// END - willChange

			});

		});


		$('#batch-add-form').submit(function(e){

			let allInputFieldsDOM = document.querySelectorAll('.for-add-batch');
			let allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add-batch');

			e.preventDefault();

			axios.post(`/ajax/inventory/products/${ productId }/batchNo`, {
				'_token' : $('input[name="_token"].for-add-batch').val(),
				'product_id' : $('input[name="product_id"].for-add-batch').val(),
				'batch_no' : $('input[name="batch_no"].for-add-batch').val(),
				'quantity' : $('input[name="quantity"].for-add-batch').val(),
				'unit_cost' : $('input[name="unit_cost"].for-add-batch').val(),
				'exp_date' : $('input[name="exp_date"].for-add-batch').val(),
				'date_added' : $('input[name="date_added"].for-add-batch').val(),
				'supplier_id' : $('select[name="supplier_id"].for-add-batch').val(),
			})
			.then((response) => {

				console.log(response.data);

				allErrorMessagesDOM.forEach((element) => element.remove() );
				allInputFieldsDOM.forEach((element) => element.value = '' );

				load_productsDatatables();
				productInfo(productId);
				openTab('batch-no', document.querySelector('.tab-for-med-modal li:nth-child(2)'));

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
				allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					$('[name="'+key+'"].for-add-batch').after('<label class="error-message for-add-batch imp">'+value+'</label>');
				})

			});

		});


		$('#batch-update-form').submit(function(e){

			var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update-batch');

			e.preventDefault();

			let batch_id = $('input[name="batch_id"].for-update-batch').val();

			axios.patch(`/ajax/inventory/batchNo/${ batch_id }`, {
				'_token' : $('input[name="_token"].for-update-batch').val(),
				'id' : $('input[name="batch_id"].for-update-batch').val(),
				'batch_no' : $('input[name="batch_no"].for-update-batch').val(),
				'supplier_id' : $('select[name="supplier_id"].for-update-batch').val(),
				'exp_date' : $('input[name="exp_date"].for-update-batch').val(),
				'date_added' : $('input[name="date_added"].for-update-batch').val(),
				'unit_cost' : $('input[name="unit_cost"].for-update-batch').val(),
				'quantity' : $('input[name="quantity"].for-update-batch').val(),
			})
			.then((response) => {

				allErrorMessagesDOM.forEach((element) => element.remove() );

				load_productsDatatables();
				productInfo(productId);
				
				openTab('batch-no', document.querySelector('.tab-for-med-modal li:nth-child(2)'));

				closeModal('edit-batch-modal');

				// reload batch number table
				batchNoTable.ajax.reload();

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

				allErrorMessagesDOM.forEach((element) => element.remove());

				$.each(errors, (key, value) => {
					if(key != 'id'){
						$('[name="'+key+'"].for-update-batch').after('<label class="error-message for-update-batch imp">'+value+'</label>');
					}
				});

			});

		});

		$('#supplier-add-form').submit(function(e){

			e.preventDefault();

			axios.post('/ajax/users/suppliers', {
				'_token' : $('input[name="_token"].for-add-supplier').val(),
				'profile_img' : $('input[name="profile_img"].for-add-supplier').val(),
				'supplier_name' : $('input[name="supplier_name"].for-add-supplier').val(),
				'address' : $('input[name="address"].for-add-supplier').val(),
				'contact_no' : $('input[name="contact_no"].for-add-supplier').val(),
			})
			.then((response) => {

				let newSupplier = $('input[name="supplier_name"].for-add-supplier').val();
				$('select[name="supplier_id"].for-add-batch').empty();

				$('select[name="supplier_id"].for-add-batch').append('<option value="">--Select Supplier--</option>');

				$.each(response.data.supplier, (key, value) => {

					let ifselected = value.supplier_name === newSupplier ? 'selected' : ' ';
					$('select[name="supplier_id"].for-add-batch').append('<option value="'+value.id+'" '+ifselected+'>'+value.supplier_name+'</option>');

					$('select[name="supplier_id"].for-update-batch').append('<option value="'+value.id+'">'+value.supplier_name+'</option>');

				});

				$('.error-message.for-add-supplier').remove();
				$('.input-field.for-add-supplier').val('');
				removeErrorMessages();

				productInfo(productId);

				closeModal('add-supplier-modal');

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
				removeErrorMessages(); //for all functions

				$.each(errors, (key, value) => $('[name="'+key+'"].for-add-supplier').after('<label class="error-message for-add-supplier imp">'+value+'</label>'));

			});

		});

		// SET HOLDER
		// $('#set-holder-form').submit( function(e) {

		// 	e.preventDefault();

		// 	// SET HOLDER
		// 	swal({
		// 		title: 'Are you sure, you want to perform any changes?',
		// 		text : 'This will cause any changes in the history of the former employee that holds the product. If yes, then click the confirm button to proceed.',
		// 		icon : 'warning',
		// 		buttons : true,
		// 		dangerMode: true,
		// 	})
		// 	.then((willChange) => {

		// 		if(willChange) {

		// 			axios.post(`/ajax/inventory/products/${ productId }/holder`, {
		// 				'_token' : $('[name="_token"].for-set-holder').val(),
		// 				'holder' : parseInt( $('[name="holder"].for-set-holder').val() ),
		// 				// 'start_date' : $('[name="start_date"].for-set-holder').val(),
		// 				// 'end_date' : $('[name="end_date"].for-set-holder').val(),
		// 			})
		// 			.then((response) => {

		// 				console.log(response.data);

		// 				removeErrorMessages();
		// 				productInfo(productId);

		// 				openTab('details');

		// 				// sweet alert
		// 				swal({
		// 					title : response.data['title'],
		// 					text : response.data['text'],
		// 					icon : 'success',
		// 					button : {
		// 						text : 'Okay',
		// 						value : true,
		// 						visible : true,
		// 						className : '',
		// 						closeModal : true,
		// 					},
		// 				});
		// 			})
		// 			.catch((error) => {

		// 				errorAlert(error);

		// 				let errors = error.response.data.errors;
		// 				removeErrorMessages(); //for all functions

		// 				$.each(errors, (key, value) => $('[name="'+key+'"].for-set-holder').after('<label class="error-message for-set-holder imp">'+value+'</label>'));

		// 			});

		// 		}// END - willChange

		// 	});

		// });

	});

	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	//					SHOWING OF DEEP DETAILS
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

	var productId;
	// var productInfoGlobal;
	function productInfo(id){
		
		productId = parseInt( id );

		$('input[name="product_id"].for-add-batch').val(id);

		getDateToday('--date-today');

		let productDetails = document.querySelector('#--product-details');
		let loading = document.querySelector('#--product-loading');

		productDetails.style.display = "none";
		loading.style.display = "block";

		axios.get(`/ajax/inventory/products/${ id }`)
			.then((response) => {

				console.log(response.data);

				let productInfo = response.data;
				// productInfoGlobal = response.data;

				let displayField = {
					'product_img' : $('#product-img-display'),
					'generic_name&strength' : $('#generic-strength-display'),
					'brand_name' : $('#brand-display'),
					'weight_volume' : $('#weight-volume-display'),
					'stock_format' : $('#quantity-display'),
					'unit_price_format' : $('#unit-price-display'),

					'holder' : $('#holder-display'),
				};

				let inputField = {
					'product_img' : $('#--product-img-edit-hidden'),
					'generic_name' : $('[name="generic_name"].for-update'),
					'brand_name' : $('[name="brand_name"].for-update'),
					'product_unit' : $('[name="product_unit"].for-update'),
					'weight_volume' : $('[name="weight_volume"].for-update'),
					'strength' : $('[name="strength"].for-update'),
					'unit_price' : $('[name="unit_price"].for-update'),
					'critical_quantity' : $('[name="critical_quantity"].for-update'),

					'holder' : $('[name="holder"].for-set-holder'),
				};

				$.each(displayField, (key, object) => {

					object.html(productInfo[key]);

					if(key == 'product_img') {
						object.attr('src', productInfo[key]);
						$('#--product-img-edit').attr('src', productInfo[key]);//so the edit tab, will also have the picture of the product
					}
					
					else if(key == 'stock_format')
						object.html(productInfo[key]);

					if(key === 'holder' && productInfo['holder'] != null)
						object.html(`<i class="ti-user text-primary"></i> <span>${ productInfo[key]['full_name'] }</span>`);
					// is no holder
					else if(key === 'holder' && productInfo['holder'] == null)
						object.html(`<i class="ti-user text-warning"></i> <span class="text-warning">NO HOLDER</span>`);

				});

				$.each(inputField, (key, object) => {

					object.val(productInfo[key]);

					// is no holder
					if(key === 'holder' && productInfo['holder'] != null)
						object.val( productInfo[key]['id'] );
					else if(key === 'holder' && productInfo['holder'] == null)
						object.val('null');

				} );

				loading.style.display = "none";
				productDetails.style.display = "block";

			})
			.catch((error) => {

				console.log(error);

			});

		// load datatables of batch numbers
		load_batchNosDatatables(id);

	}

	function batchInfo(id){

		let batchEdit = document.querySelector('#--batch-edit');
		let loading = document.querySelector('#--batch-loading');

		batchEdit.style.display = "none";
		loading.style.display = "block";

		axios.get(`/ajax/inventory/batchNo/${ id }`)
			.then((response) => {

				let batchInfos = response.data;

				let inputField = {
					'id' : $('input[name="batch_id"].for-update-batch'),
					'batch_no' : $('input[name="batch_no"].for-update-batch'),
					'supplier_id' : $('select[name="supplier_id"].for-update-batch'),
					'exp_date' : $('input[name="exp_date"].for-update-batch'),
					'date_added' : $('input[name="date_added"].for-update-batch'),
					'unit_cost' : $('input[name="unit_cost"].for-update-batch'),
					'quantity' : $('input[name="quantity"].for-update-batch'),
				};
				
				$.each(inputField, (key, object) => object.val(batchInfos[key]));

				batchEdit.style.display = "block";
				loading.style.display = "none";

			});
	}

	var batchNoId;
	function lossBatchNo(id, maxQuantity)
	{
		// setting the batch number that is loss
		batchNoId = id;
		// setting the max quantity
		$('[name="quantity"].for-add-loss').attr('max', maxQuantity);
		// removing all input values
		document.querySelectorAll('.for-add-loss').forEach(function(object, index){ object.value = ''; } );
	}

	$(document).ready( function() {

		$('[name="quantity"].for-add-loss').keyup(function(){
			
			if(parseInt($(this).attr('max')) < parseInt($(this).val()))
				$(this).val( $(this).attr('max') );

			else if(parseInt($(this).val()) < 1)
				$(this).val(1);

		});

		// loss form
		$('#loss-form').submit(function(e){

			e.preventDefault();

			swal({
				title : 'Are you sure, that this product\'s batch number is loss?',
				text : 'If yes, then simply click the confirm button to continue. Please wait for the success message and then proceed.',
				icon : 'warning',
				buttons : {
					cancel : 'Cancel',
					confirm : {
						text : 'Confirm',
						value : 'confirm',
					}
				},
			})
			.then((value) => {

				switch(value)
				{
					// IF LICKED THE BUTTON CONFIRM
					case 'confirm':
						let allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add-loss');

						axios.post(`/ajax/inventory/loss/${ batchNoId }`,{
							'loss_date' : $('[name="loss_date"].for-add-loss').val(),
							'quantity' : $('[name="quantity"].for-add-loss').val(),
							'reason' : $('[name="reason"].for-add-loss').val(),
						})
						.then((response) => {

							console.log(response.data);

							swal({
								title : response.data['title'],
								text : response.data['text'],
								icon : 'success',
								button : {
									text : 'Okay',
								}
							});

							load_productsDatatables();
							productInfo(productId);
							closeModal('loss-modal');

						})
						.catch((error) => {

							errorAlert(error);

							console.log(error.response);

							let errors = error.response.data.errors;

							allErrorMessagesDOM.forEach((element) => element.remove());

							$.each(errors, (key, value) => {
								let DOM = document.querySelector('[name="'+key+'"].for-add-loss');
								DOM.insertAdjacentHTML('afterend', '<label class="error-message for-add-loss imp">'+value+'</label>');
							})

						});
					break;
				}

			})

		});

	}); // end of document.ready function


	// ******************************************************************
	// function dependencies()
	// {
	// 	axios.get(`/ajax/employee`)
	// 		.then()
	// 		.catch();
	// }
	// ******************************************************************
