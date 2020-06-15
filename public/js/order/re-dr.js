// for return
function details(id, accountID)
{
	$('[name="account_id"].for-re-delivery').val(accountID);

	load_orderMedicineDatatables(id);
}

var replacedProductTable;
// loading of ordered medicine
function load_orderMedicineDatatables(id)
{
	replacedProductTable = $('#replaced-product-table').DataTable({
		processing : true,
		serverSide : true,
		deferRender : true,
		ajax : `/ajax/order/re-dr/${ id }`,

		destroy : true,

		sorting : false,
		searching : false,
		paging : false,
		info : false,

		responsive : true,

		// order : [ [1, 'asc'] ],

		// exercise
		autoWidth : false, // fast initiliazation
		columnDefs: [
			{ width : '20%', 'targets' : [ ] }
		],

		columns : [
			{
				data : 'product_img',
				name : 'product_img',
				orderable : false,
				searchable : false,
			},
			{
				data : 'product_name',
				name : 'product_name',
				orderable : false,
			},
			{
				data : 'batch_nos',
				name : 'batch_nos',
				orderable : false,
			},
			{
				data : 'total_quantity_format',
				name : 'total_quantity',
				orderable : false,
			},
			// {
			// 	data : 'action',
			// 	name : 'action',
			// 	orderable : false,
			// },
		]
	});
}

	// DELIVERY FORM
	$('#re-delivery-form').on('submit', function(e){

		e.preventDefault();

		swal({
			title: 'Are you sure, the suggested batch numbers is the one you prefered for replacing?',
			text : 'If yes, then just click the confirm button.',
			icon : 'warning',
			buttons : true,
			dangerMode: true,
		})
		.then((confirmation) => {
			if(confirmation) {
				
				var receipt_no = '';
				if($('[name="receipt_no"].for-re-delivery').val() != '')
					receipt_no = $('[name="receipt_type"].for-re-delivery').val() + $('[name="receipt_no"].for-re-delivery').val();

				// order medicine
				var order_medicine = [];
				$.each( $('.--medicine.for-re-delivery'), (key, object)  => {
					// get product id
					let product_id = $(object).val();
					// get used batch numbers 
					let used_batch_nos = [];
					$.each( $(`.--medicine-${ product_id }`), (key, object) => {
						used_batch_nos.push({
							batch_id : $(object).attr('data-batch-no-id'),
							batch_quantity : $(object).attr('data-batch-no-quantity'),
						});
					} )

					// returned order medicine id
					let returned_order_medicine_ids = [];
					$.each( $(object).closest('tr').find('.--returned-order-medicine-id'), (key, object) => {
						// push returned order medicine ids...
						returned_order_medicine_ids.push( parseInt($(object).val()) );
					} );

					// push new product - replaced products
					order_medicine.push({
						'product_id' : product_id,
						'used_batch_nos' : used_batch_nos,
						'returned_order_medicine_ids' : returned_order_medicine_ids,
					});
				} );

				// post send re dr
				axios.post(`/ajax/order/re-dr`, {
					'_token' : $('[name="_token"]').val(),
					'receipt_no' : receipt_no,
					'delivery_date' : $('[name="delivery_date"].for-re-delivery').val(),
					'employee_id' : $('[name="employee_id"].for-re-delivery').val(),
					// order transaction
					'order_transaction': {
						'account_id' : $('[name="account_id"].for-re-delivery').val(),
						'status' : 'Delivered',
						'order_date' : $('.--order_transaction_order_date').val(),
						'employee_id' : $('[name="employee_id"].for-re-delivery').val(),
						'replacement' : true,
					},
					// order medicine
					'order_medicine' : order_medicine,
				})
				.then((response) => {
					console.log(response.data);
					// reload
					replacedProductTable.ajax.reload();
					reDRTable.ajax.reload();
					// sweet alert
					successAlert(response);
				})
				.catch((error) => {

					console.log(error.response);

					errorAlert(error);

					// display errors
					let errors = error.response.data.errors;
					$.each(errors, (key, value) => {
						$(`[name="${ key }"].for-re-delivery`).addClass('is-invalid');
						$(`[name="${ key }"].for-re-delivery`).closest('.form-group').append(`
		                    <h6 class="error-message for-re-delivery text-danger font-12 lighter">
		                        ${ value }
		                    </h6>`);
					})
				});

			}
		});

	});


	// confirmation before rejecting the current request
	$('#re-dr-table').on('click', '.--delete-btn', function(){

		let order_transaction_id = $(this).data('order-transaction-id');

		swal({
			title: 'Are you sure, you want to dispose the request to replace the products for this transaction?',
			text : 'If yes, then click the confirm button to proceed.',
			icon : 'warning',
			buttons : true,
			dangerMode: true,
		})
		.then((confirm) => {
			if(confirm) {
				
				axios.delete(`/ajax/order/re-dr/${ order_transaction_id }`)
					.then( response => {

						console.log( response.data );

						reDRTable.ajax.reload();

						successAlert( response );

					} )
					.catch( error => {

						console.log(error);

						errorAlert( error );

					} );
			}
		});

	});