
// for all of the tabs their
// var tabs = ['details', 'batch-no', 'edit-info', 'add-batch-no', /*'set-holder'*/];
// end of for all of the tabs their
	
$(document).ready(function(){

	$('#product-add-form').submit(function(e){

		e.preventDefault();

		// let productFormData = new FormData( this );

		axios.post('/ajax/inventory/products', new FormData( this ))
		.then((response) => {

			console.log(response.data);

			productTable.ajax.reload();

			successAlert(response);

			// $('#add-new-product-modal').modal('hide');

		})
		.catch((error) => {

			errorAlert(error);

			console.log(error.response);

			let errors = error.response.data.errors;

			// error message 
			$('.error-message').remove();

			$.each(errors, (key, value) => {
				$(`[name="${ key }"].for-add-product`).addClass('is-invalid');
				$(`[name="${ key }"].for-add-product`).closest('.form-group').append(`
                    <h6 class="error-message for-add-product text-danger font-12 lighter">
                        ${ value }
                    </h6>`);
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

				// var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-product-update');

				console.log( $(this).serialize() );

				axios.post(`/ajax/inventory/products/${ productId }`, new FormData( this ))
					.then((response) => {

						console.log(response.data);

						// product table reload
						productTable.ajax.reload();

						productInfo(productId);

						successAlert(response);

					})
					.catch((error) => {

						console.log(error);

						errorAlert(error);

						let errors = error.response.data.errors;

						console.log(error.response);

						$('.error-message').remove();

						$.each(errors, (key, value) => {
							$(`[name="${ key }"].for-update-product`).addClass('is-invalid');
							$(`[name="${ key }"].for-update-product`).closest('.form-group').append(`
			                    <h6 class="error-message for-update-product text-danger font-12 lighter">
			                        ${ value }
			                    </h6>`);
						})

					});
			}// END - willChange

		});

	});


	$('#batch-add-form').submit(function(e){

		// let allInputFieldsDOM = document.querySelectorAll('.for-add-batch');
		// let allErrorMessagesDOM = document.querySelectorAll('.error-message.for-add-batch');

		e.preventDefault();

		axios.post(`/ajax/inventory/products/${ productId }/batchNo`, $(this).serialize() )
		.then((response) => {

			console.log(response.data);

			// allErrorMessagesDOM.forEach((element) => element.remove() );
			// allInputFieldsDOM.forEach((element) => element.value = '' );

			load_productsDatatables();
			productInfo(productId);

			// sweet alert
			successAlert( response );

		})
		.catch((error) => {

			errorAlert(error);

			console.log(error.response);

			let errors = error.response.data.errors;

			$('.error-message').remove();

			$.each(errors, (key, value) => {
				$(`[name="${ key }"].for-add-batch`).addClass('is-invalid');
				$(`[name="${ key }"].for-add-batch`).closest('.form-group').append(`
                    <h6 class="error-message for-add-batch text-danger font-12 lighter">
                        ${ value }
                    </h6>`);
			})

		});

	});


	$('#batch-update-form').submit(function(e){

		// var allErrorMessagesDOM = document.querySelectorAll('.error-message.for-update-batch');

		e.preventDefault();

		let batch_id = $('[name="batch_no_id"].for-update-batch').val();

		axios.patch(`/ajax/inventory/batchNo/${ batch_id }`, $(this).serialize() )
			.then((response) => {

				// allErrorMessagesDOM.forEach((element) => element.remove() );

				productTable.ajax.reload();
				productInfo(productId);

				// reload batch number table
				batchNoTable.ajax.reload();

				successAlert(response);

			})
			.catch((error) => {

				errorAlert(error);

				let errors = error.response.data.errors;

				$('.error-message').remove();

				$.each(errors, (key, value) => {
					$(`[name="${ key }"].for-update-batch`).addClass('is-invalid');
					$(`[name="${ key }"].for-update-batch`).closest('.form-group').append(`
	                    <h6 class="error-message for-udpate-batch text-danger font-12 lighter">
	                        ${ value }
	                    </h6>`);
				})

			});

	});

});

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//					SHOWING OF DEEP DETAILS
//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

var productId;
// var productInfoGlobal;
function productInfo(id){
	
	productId = parseInt( id );

	$('[name="product_id"].for-add-batch').val(id);

	let loading = new ModalLoading('#product-details-modal');
	loading.setLoading();

	axios.get(`/ajax/inventory/products/${ id }`)
		.then((response) => {

			loading.removeLoading();

			console.log(response.data);

			let productInfo = response.data['product'];
			// productInfoGlobal = response.data;

			// TAB 1 DISPLAY
			$('#tab-1').html( response.data['product_html'] );

			let inputField = {
				'product_img' : $('[name="product_img_hidden"].for-update-product'),

				'generic_name' : $('[name="generic_name"].for-update-product'),
				'brand_name' : $('[name="brand_name"].for-update-product'),
				'product_unit' : $('[name="product_unit"].for-update-product'),
				'weight_volume' : $('[name="weight_volume"].for-update-product'),
				'strength' : $('[name="strength"].for-update-product'),
				'unit_price' : $('[name="unit_price"].for-update-product'),
				'critical_quantity' : $('[name="critical_quantity"].for-update-product'),
			};

			$.each(inputField, (key, object) => {
				if(key == 'product_img') {
					object.val(productInfo[key]);
					$('[name="product_img"].for-update-product ~ .dropify-preview .dropify-render img').attr('src', productInfo[key]);
				}
				else
					object.val(productInfo[key]);
			} );

			// SET PRODUCT ID FOR BATCH NUMBER
			$('[name="product_id"].for-add-batch').val( productInfo['id'] );
			$('[name="product_id"].for-update-batch').val( productInfo['id'] );

		})
		.catch((error) => {

			console.log(error);

		});

	// load datatables of batch numbers
	load_batchNosDatatables(id);

}

function batchInfo(id){

	let loading = new ModalLoading('#edit-batch-no-modal');
	loading.setLoading();

	axios.get(`/ajax/inventory/batchNo/${ id }`)
		.then((response) => {

			loading.removeLoading();

			let batchnoDatas = response.data;

			console.log( batchnoDatas );

			let inputField = {
				'id' : $('[name="batch_no_id"].for-update-batch'),
				'batch_no' : $('[name="batch_no"].for-update-batch'),
				'supplier' : $('[name="supplier_id"].for-update-batch'),
				'exp_date' : $('[name="exp_date"].for-update-batch'),
				'date_added' : $('[name="date_added"].for-update-batch'),
				'unit_cost' : $('[name="unit_cost"].for-update-batch'),
				'quantity' : $('[name="quantity"].for-update-batch'),
			};
			
			$.each(inputField, (key, object) => {
				if(key == 'supplier')
					object.val( batchnoDatas[key]['id'] )
				else
					object.val( batchnoDatas[key] )
			});

		});
}

var batchNoId;
function lossBatchNo(id, maxQuantity)
{
	// setting the batch number that is loss
	batchNoId = id;
	// setting the max quantity
	$('[name="quantity"].for-add-loss').attr('max', maxQuantity);
	// // removing all input values
	// document.querySelectorAll('.for-add-loss').forEach(function(object, index){ object.value = ''; } );
}

$(document).ready( function() {
	// loss form
	$('#loss-product-form').submit(function(e){

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
					axios.post(`/ajax/inventory/loss/${ batchNoId }`, $(this).serialize() )
					.then((response) => {

						console.log(response.data);

						successAlert(response);

						productTable.ajax.reload();
						productInfo(productId);
						
						$('#loss-product-modal').modal('hide');
						$('.modal-backdrop.fade.show').remove();

					})
					.catch((error) => {

						errorAlert(error);

						console.log(error.response);

						let errors = error.response.data.errors;

						$.each(errors, (key, value) => {
							$(`[name="${ key }"].for-add-loss`).addClass('is-invalid');
							$(`[name="${ key }"].for-add-loss`).closest('.form-group').append(`
			                    <h6 class="error-message for-add-loss text-danger font-12 lighter">
			                        ${ value }
			                    </h6>`);
						})

					});
				break;
			}

		})

	});

}); // end of document.ready function


// **********************************
// 			FILTERING DATES
// **********************************
var start = moment().startOf('month');
var end = moment().endOf('month');

function cb(start, end) {

    // setting the value
    $('[name="from_date"].for-filtering-dates').val( start.format('YYYY-MM-DD') );
    $('[name="to_date"].for-filtering-dates').val( end.format('YYYY-MM-DD') );

    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

    // reload filter tables
	// productTable.ajax.data;
	// reload datatables
	// productTable.ajax.reload();
}

// set date range picker
$(document).ready(function(){

	$('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    
});

cb(start, end);